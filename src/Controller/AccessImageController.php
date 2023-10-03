<?php

namespace App\Controller;


use App\Entity\Image;
use App\Service\ImageService;
use League\Flysystem\FilesystemException;
use League\Glide\Server;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/access-image')]
class AccessImageController extends AbstractController
{
    public function __construct(private readonly ImageService $imageService)
    {
    }


}
