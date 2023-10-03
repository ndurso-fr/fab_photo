<?php

namespace App\Controller;

use App\Entity\Image;
use App\Form\ImageUploaderType;
use App\Repository\ImageRepository;
use App\Service\ImageService;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemException;
use League\Glide\Server;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/image')]
class ImageController extends AbstractController
{
    public function __construct(private readonly ImageService $imageService,
                                private ImageRepository $imageRepository)
    {
    }

    #[Route('/', name: 'app_image_index', methods: ['GET'])]
    public function index(): Response
    {
//        return $this->render('image/index.html.twig', [
//            'images' => $this->imageService->encryptUrlsWithGlide(['w' => 50, 'h' => 50]),
//        ]);

        return $this->render('image/index.html.twig', [
            'images' => $this->imageRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_image_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $image = new Image();
        $form = $this->createForm(ImageUploaderType::class, $image);
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
// pour utiliser : <img src="{{ path('access_glide_secure_image', {'id': image.id}) }}" alt="{{ image.imageName }}">
//        return $this->render('image/show.html.twig', [
//            'image' => $image,
//        ]);

        // pour utiliser : <img src="{{ image.glideUrl }}" alt="{{ image.imageName }}">
//        return $this->render('image/show.html.twig', [
//            'image' => $this->imageService->encryptUrlWithGlide($image, []),
//        ]);

        return $this->render('image/show.html.twig', [
            'image' => $image,
        ]);
    }

//    #[Route('/{id}/edit', name: 'app_image_edit', methods: ['GET', 'POST'])]
//    public function edit(Request $request, Image $image, EntityManagerInterface $entityManager): Response
//    {
//        $form = $this->createForm(ImageUploaderType::class, $image);
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
            $this->imageService->photoStorage->delete($image->getImageName());
            // delete image reference into BDD
            $entityManager->remove($image);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_image_index', [], Response::HTTP_SEE_OTHER);
    }

//    #[Route('/decryptThumbnailImage/{glideUrl}', name: 'access_glide_Thumbnail', methods: ['GET'])]
//    public function decryptThumbnailImage(Request $request, Server $glide, string $glideSignKey, string $glideUrl): Response
//    {
//        // avec l'ajout de "glide security server" au lieu de mettre la route du controller connait le path de l'image
//        // on met une variable qui contient la route du controller qui sait décrypter la secure key, puis retrouver le path caché par flysystem
//        return $this->imageService->decryptUrlFromGlideSecureUrl($request, $glide, $glideSignKey, $glideUrl);
//    }

    /**
     * @throws \Exception
     */
    #[Route('/getImageWithGlide/{imageName}', name: 'access_glide_Thumbnail', methods: ['GET'])]
    public function getImageWithGlide(Request $request, Server $glide, string $glideSignKey, string $imageName): Response
    {
        return $this->imageService->findImageFromSecureRoute($request, $glide, $glideSignKey, $imageName);
    }

    #[Route('/getImage/{id}', name: 'access_glide_secure_image', methods: ['GET'])]
    public function getImageFromFlysystem(Request $request, Image $image, $glideSignKey): Response
    {
        // On n'est pas obligé de laisser glide (grace sa config, voir service.yaml) demander à flysystem
        // le chemin où est l'image.
        // On peut le faire nous même, mais protéger avec Glide cet url qui récupère le chemin vers l'image
        // appelé ainsi : <img src="{{ path('access_glide_secure_image', {'id': image.id}) }}" alt="{{ image.imageName }}">
        try {
            $response = new Response($this->imageService->photoStorage->read($image->getImageName()));
            $response->headers->set('Content-Type', $this->imageService->photoStorage->mimeType($image->getImageName()));
            $response->setEtag(md5($response->getContent()));
            $response->setSharedMaxAge(31536000);
            $response->setPublic();
            $response->isNotModified($request);

            return $response;
        } catch (FilesystemException) {
            throw new NotFoundHttpException("Ce fichier n'existe pas");
        }
    }

    #[Route('/download/{fileName}/{name}/{size}', name: 'download_image', methods: ['GET'])]
    public function download(string $fileName, string $name, string $size): StreamedResponse
    {
        /*
         * <a href="{{ path('download_image', { 'fileName' : image.imageName, 'name' : image.originalName, 'size' : image.imageSize}) }}">download</a>
         */
        try {
            $stream = $this->imageService->photoStorage->readStream($fileName);

            return new StreamedResponse(function () use ($stream) {
                fpassthru($stream);
                exit();
            }, 200, [
                'Content-Transfer-Encoding', 'binary',
                'Content-Type' => $this->imageService->photoStorage->mimeType($fileName),
                'Content-Disposition' => 'attachment; filename='.$name,
                'Content-Length' => $size,
            ]);
        } catch (FilesystemException) {
            throw new NotFoundHttpException("Ce fichier n'existe pas");
        }
    }
}
