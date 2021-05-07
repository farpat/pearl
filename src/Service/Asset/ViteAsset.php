<?php

namespace App\Service\Asset;


use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ViteAsset implements AssetInterface
{
    private ParameterBagInterface $parameterBag;
    private int                   $assetDevServerPort;
    private ?array                $manifestJson;

    public function __construct(ParameterBagInterface $parameterBag, int $assetDevServerPort)
    {
        $this->parameterBag = $parameterBag;
        $this->assetDevServerPort = $assetDevServerPort;
        $this->manifestJson = null;
    }

    public function renderAsset(string $asset): string
    {
        $manifestPath = $this->parameterBag->get('kernel.project_dir') . '/public/assets/manifest.json';

        if (file_exists($manifestPath)) {
            if ($this->manifestJson === null) {
                $this->manifestJson = json_decode(file_get_contents($manifestPath), true);
            }

            $asset = trim($asset, '/');
            $data = $this->manifestJson['js/' . $asset] ?? null;
            if ($data === null) {
                return '';
            }
            $jsFile = $data['file'];
            $cssFiles = $data['css'] ?? [];

            $html = "<script src=\"/assets/{$jsFile}\" type=\"module\" defer></script>";
            foreach ($cssFiles as $css) {
                $html .= "<link rel=\"stylesheet\" href=\"/assets/{$css}\" media=\"screen\"/>";
            }

            return $html;
        } else {
            $base = "http://localhost:{$this->assetDevServerPort}/assets/js";

            dump($base . '/' . $asset);

            return <<<HTML
<script type="module" src="{$base}/@vite/client"></script>
<script src="{$base}/{$asset}" type="module" defer></script>
HTML;
        }
    }
}
