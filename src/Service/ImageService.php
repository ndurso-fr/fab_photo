<?php

namespace App\Service;

use App\Entity\Image;
use App\Repository\ImageRepository;
use League\Flysystem\FilesystemOperator;
use League\Glide\Responses\SymfonyResponseFactory;
use League\Glide\Server;
use League\Glide\Signatures\SignatureException;
use League\Glide\Signatures\SignatureFactory;
use League\Glide\Urls\UrlBuilder;
use League\Glide\Urls\UrlBuilderFactory;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ImageService
{
    public function __construct(public readonly FilesystemOperator $photoStorage,
                                private ImageRepository $imageRepository,
                                private string $glideSignKey)
    {
    }

    /**
     * @param string $imageName : must match with fileNameProperty variable into Image or Media class
     * @param array $params
     * @return string
     */
    public function secureRouteForViewUrl(string $imageName, array $params=[]): string
    {
        return UrlBuilderFactory::create('/image/getImageWithGlide/', $this->glideSignKey)->getUrl($imageName, $params);
    }
    public function findImageFromSecureRoute(Request $request, Server $glide, string $glideSignKey, string $imageName): Response
    {
        try {
            $parameters = $request->query->all();

            SignatureFactory::create($glideSignKey)->validateRequest('/image/getImageWithGlide/' . $imageName, $parameters);

            $glide->setResponseFactory(new SymfonyResponseFactory($request));

            return $glide->getImageResponse($imageName, $parameters);
        } catch (SignatureException) {
            throw new AccessDeniedException($imageName);
        } catch (\Exception $ex) {
            throw new \Exception($ex);
        }
    }

    // when controller give to a the view a secure url
    public function encryptUrlsWithGlide(array $glideParameters): array
    {
        $images = [];
        // création d'un url sécurisée avec la route /access-image/glideImage/
        // + le chemin connu par flysytem
        // + la clé
        // + les param pour mignaturiser
        // On encrypte l'url du controller qui sait retrouver l'url même avec la signature sécurisée
        $urlShowBuilder = $this->encryptRouteForController('/image/decryptThumbnailImage/');
        foreach ($this->imageRepository->findAll() as $image) {
            $secureUrl = $urlShowBuilder->getUrl($image->getImageName(), $glideParameters);
            $image->setGlideUrl($secureUrl);
            $images[] = $image;
        }
        return $images;
    }

    public function encryptUrlWithGlide(Image $image, array $glideParameters): Image
    {
        $urlShowBuilder = $this->encryptRouteForController('/image/decryptThumbnailImage/');
        $secureUrl = $urlShowBuilder->getUrl($image->getImageName(), $glideParameters);
        $image->setGlideUrl($secureUrl);

        return $image;
    }
    private function encryptRouteForController(string $route): UrlBuilder
    {
        return UrlBuilderFactory::create($route, $this->glideSignKey);
    }


    public function decryptUrlFromGlideSecureUrl(Request $request, Server $glide, string $glideSignKey, string $secureUrl): Response
    {
        try {
            $parameters = $request->query->all();
            // il déchiffre : enlève de la chaine de caractère la route qui est dans l'url sécurisée,
            // déchiffre la clé sécurisée et demande à flysystem l'url de l'image
            SignatureFactory::create($glideSignKey)->validateRequest('/image/decryptThumbnailImage/' . $secureUrl, $parameters);

            $glide->setResponseFactory(new SymfonyResponseFactory($request));

            return $glide->getImageResponse($secureUrl, $parameters);
        } catch (SignatureException) {
            throw new AccessDeniedException($secureUrl);
        } catch (\Exception $ex) {
            throw new \Exception($ex);
        }
    }

}