<?php

namespace App\Twig;
use App\Service\ImageService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppImageExtension extends AbstractExtension
{
    public function __construct(private readonly ImageService $imageService)
    {
    }
    public function getFunctions(): array
    {
        return [
            new TwigFunction('image_glide_helper', [$this, 'imageGlideHelper']),
        ];
    }

    public function imageGlideHelper(string $imageName, array $params=[]): string
    {
        return $this->imageService->secureRouteForViewUrl($imageName, $params);
    }
}