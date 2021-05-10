<?php

namespace App\Twig;

use App\Service\Asset\AssetInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AssetExtension extends AbstractExtension
{
    private AssetInterface $asset;

    public function __construct(AssetInterface $asset)
    {
        $this->asset = $asset;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('render_assets', [$this, 'renderAsset'], ['is_safe' => ['html']]),
        ];
    }

    public function renderAsset(string $entry): string
    {
        return $this->asset->renderAsset($entry);
    }
}
