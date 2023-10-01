<?php

namespace App\Twig;
use App\Entity\Image;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppImageExtension extends AbstractExtension
{

    public function getFunctions(): array
    {
        return [
            new TwigFunction('image_glide_helper', [$this, 'imageGlideHelper']),
        ];
    }

    public function imageGlideHelper(Image $image, array $params=[]): string
    {
        return "";
    }
}