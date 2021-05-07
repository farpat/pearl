<?php

namespace App\Service\Asset;


use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

interface AssetInterface
{
    public function __construct(
        string $manifestJsonPath,
        bool $isLegacyBrowser,
        int $assetDevServerPort
    );

    public function renderAsset(string $asset): string;
}