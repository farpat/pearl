<?php

namespace App\Service\Asset;


class ViteAsset implements AssetInterface
{
    private string $manifestJsonPath;
    private bool   $isLegacyBrowser;
    private int    $assetDevServerPort;
    private ?array $manifestJson;

    public function __construct(
        string $manifestJsonPath,
        bool $isLegacyBrowser,
        int $assetDevServerPort
    ) {
        $this->assetDevServerPort = $assetDevServerPort;
        $this->manifestJson = null;
        $this->isLegacyBrowser = $isLegacyBrowser;
        $this->manifestJsonPath = $manifestJsonPath;
    }

    public function renderAsset(string $asset): string
    {
        if (file_exists($this->manifestJsonPath)) {
            if ($this->manifestJson === null) {
                $this->manifestJson = json_decode(file_get_contents($this->manifestJsonPath), true);
            }

            $key = 'js/' . $asset;

            $legacyKey = $this->isLegacyBrowser ?
                'js/' . str_replace('.js', '-legacy.js', $asset) :
                null;

            $data = $this->manifestJson[$legacyKey ?? $key] ?? null;
            if ($data === null) {
                return '';
            }
            $jsFile = $data['file'];
            $cssFiles = $this->manifestJson[$key]['css'] ?? [];

            $html = $this->isLegacyBrowser ?
                "<script src=\"/assets/{$jsFile}\" nomodule defer></script>" :
                "<script src=\"/assets/{$jsFile}\" type=\"module\" defer></script>";

            foreach ($cssFiles as $css) {
                $html .= "<link rel=\"stylesheet\" href=\"/assets/{$css}\" media=\"screen\"/>";
            }

            return $html;
        } else {
            $base = "http://localhost:{$this->assetDevServerPort}/assets";

            return <<<HTML
<script type="module" src="{$base}/@vite/client"></script>
<script src="{$base}/js/{$asset}" type="module" defer></script>
HTML;
        }
    }
}
