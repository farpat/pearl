<?php

namespace App\Service\Asset;


use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class AssetFactory
{
    private ParameterBagInterface $parameterBag;
    private ?Request              $request;

    public function __construct(ParameterBagInterface $parameterBag, RequestStack $requestStack)
    {
        $this->parameterBag = $parameterBag;
        $this->request = $requestStack->getCurrentRequest();
    }

    public function createAssetService(): AssetInterface
    {
        return new ViteAsset(
            $this->parameterBag->get('kernel.project_dir') . '/public/assets/manifest.json',
            $this->isLegacyBrowser(),
            3000
        );
    }

    private function isLegacyBrowser(): bool
    {
        if ($this->request === null) {
            return false;
        }

        $userAgent = $this->request->headers->get('User-Agent');

        if (strpos($userAgent, 'MSIE') !== false) {
            return true;
        }

        if (strpos($userAgent, 'Windows NT') !== false) {
            return true;
        }

        if (strpos($userAgent, 'Internet Explorer') !== false) {
            return true;
        }


        return false;
    }
}