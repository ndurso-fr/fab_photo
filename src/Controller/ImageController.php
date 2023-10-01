<?php

namespace App\Controller;

use App\Entity\Image;
use App\Form\ImageType;
use App\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;
use League\Glide\Responses\SymfonyResponseFactory;
use League\Glide\Server;
use League\Glide\Signatures\SignatureException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/image')]
class ImageController extends AbstractController
{
    public function __construct(private readonly FilesystemOperator $photoStorage)
    {
    }

    #[Route('/', name: 'app_image_index', methods: ['GET'])]
    public function index(ImageRepository $imageRepository): Response
    {
        return $this->render('image/index.html.twig', [
            'images' => $imageRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_image_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $image = new Image();
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($image);
            $entityManager->flush();

            return $this->redirectToRoute('app_image_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('image/new.html.twig', [
            'image' => $image,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_image_show', methods: ['GET'])]
    public function show(Image $image): Response
    {
        return $this->render('image/show.html.twig', [
            'image' => $image,
        ]);
    }

//    #[Route('/{id}/edit', name: 'app_image_edit', methods: ['GET', 'POST'])]
//    public function edit(Request $request, Image $image, EntityManagerInterface $entityManager): Response
//    {
//        $form = $this->createForm(ImageType::class, $image);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager->flush();
//
//            return $this->redirectToRoute('app_image_index', [], Response::HTTP_SEE_OTHER);
//        }
//
//        return $this->render('image/edit.html.twig', [
//            'image' => $image,
//            'form' => $form,
//        ]);
//    }

    #[Route('/show-delete-form/{id}', name: 'show_delete_image')]
    public function showDeleteForm(Image $image): Response
    {
        return $this->render('image/_delete_form.html.twig', ['image' => $image]);
    }

    #[Route('/{id}', name: 'app_image_delete', methods: ['POST'])]
    public function delete(Request $request, Image $image, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$image->getId(), $request->request->get('_token'))) {
            // delete image into storage dick
            $this->photoStorage->delete($image->getImageName());
            // delete image reference into BDD
            $entityManager->remove($image);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_image_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/download/{fileName}/{name}/{size}', name: 'download_image', methods: ['GET'])]
    public function download(string $fileName, string $name, string $size): StreamedResponse
    {
    /*
     * <a href="{{ path('download_image', { 'fileName' : image.imageName, 'name' : image.originalName, 'size' : image.imageSize}) }}">download</a>
     */
        try {
            $stream = $this->photoStorage->readStream($fileName);

            return new StreamedResponse(function () use ($stream) {
                fpassthru($stream);
                exit();
            }, 200, [
                'Content-Transfer-Encoding', 'binary',
                'Content-Type' => $this->photoStorage->mimeType($fileName),
                'Content-Disposition' => 'attachment; filename='.$name,
                'Content-Length' => $size,
            ]);
        } catch (FilesystemException) {
            throw new NotFoundHttpException("Ce fichier n'existe pas");
        }
    }
    #[Route('/getImage/{id}', name: 'app_get_image', methods: ['GET'])]
    public function getImageFromFlysystem(Request $request, Image $image): Response
    {
        try {
            $response = new Response($this->photoStorage->read($image->getImageName()));
            $response->headers->set('Content-Type', $this->photoStorage->mimeType($image->getImageName()));
            $response->setEtag(md5($response->getContent()));
            $response->setSharedMaxAge(31536000);
            $response->setPublic();
            $response->isNotModified($request);

            return $response;
        } catch (FilesystemException) {
            throw new NotFoundHttpException("Ce fichier n'existe pas");
        }
    }

    #[Route('/glideImage/{id}', name: 'app_glide_image', methods: ['GET'])]
    public function glideImage(Request $request, Image $image, Server $glide, string $glideSignKey=""): Response
    {
        // Glide fait comme on a fait dans le controller getImageFromFlysystem
        // Grâce à la configuration de son service, on lui dit d'utiliser flysystem pour la retrouver.
        // voir : ~/fab_photo/config/services.yaml
        try {
            $parameters = $request->query->all();
            //SignatureFactory::create($glideSignKey)->validateRequest('/getImage/' . $_image->getImageName(), $parameters);

            $glide->setResponseFactory(new SymfonyResponseFactory($request));

            return $glide->getImageResponse($image->getImageName(), ['w' => 50, 'h' => 50]);
        } catch (SignatureException) {
            throw new AccessDeniedException($image->getImageName());
        } catch (FilesystemException) {
            throw new NotFoundHttpException("Ce fichier n'existe pas");
        }
    }
}
