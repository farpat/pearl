<?php

namespace App\Service\Asset;

class ViteAsset implements AssetInterface
{
    private string $manifestJsonPath;
    private bool   $isLegacyBrowser;
    private int    $assetDevServerPort;
    private ?array $manifestJson;

    public function __construct(string $manifestJsonPath, bool $isLegacyBrowser, int $assetDevServerPort)
    {
        $this->assetDevServerPort = $assetDevServerPort;
        $this->manifestJson = null;
        $this->isLegacyBrowser = $isLegacyBrowser;
        $this->manifestJsonPath = $manifestJsonPath;
    }

    /**
     * @throws AssetException
     */
    public function renderAsset(string $entry, array $dependencies = []): string
    {
        if (file_exists($this->manifestJsonPath)) {
            if ($this->manifestJson === null) {
                $this->manifestJson = json_decode(file_get_contents($this->manifestJsonPath), true);
            }

            ['script' => $script, 'cssFiles' => $cssFiles, 'importFiles' => $importFiles] = $this->getData($entry);

            $html = '';
            foreach ($dependencies as $dependency) {
                $html .= $dependency;
            }
            $html .= $this->renderProductionScript($script);
            $html .= $this->renderProductionStyles($cssFiles);
            $html .= $this->renderProductionImports($importFiles);

            return $html;
        }

        return $this->renderDevScript($entry);
    }

    /**
     * @return array{script: string, cssFiles: string[], importFiles: string[]}
     * @throws AssetException
     */
    private function getData(string $asset): array
    {
        $key = 'js/' . $asset;

        $legacyKey = $this->isLegacyBrowser ?
            'js/' . str_replace('.js', '-legacy.js', $asset) :
            null;

        $data = $this->manifestJson[$legacyKey ?? $key] ?? null;
        if ($data === null) {
            throw new AssetException("L'entrée << $key (ou $legacyKey) >> n'existe pas !");
        }

        return [
            'script'      => $data['file'],
            'cssFiles'    => $this->manifestJson[$key]['css'] ?? [],
            'importFiles' => $data['imports'] ?? []
        ];
    }

    private function renderProductionScript(string $file): string
    {
        return $this->isLegacyBrowser ?
            "<script src=\"/assets/{$file}\" nomodule defer></script>" :
            "<script src=\"/assets/{$file}\" type=\"module\" defer></script>";
    }

    private function renderProductionStyles(array $files): string
    {
        $html = '';
        foreach ($files as $file) {
            $html .= "<link rel=\"stylesheet\" href=\"/assets/{$file}\" media=\"screen\"/>";
        }
        return $html;
    }

    private function renderProductionImports(array $files): string
    {
        $html = '';
        foreach ($files as $file) {
            $html .= "<link rel=\"modulepreload\" href=\"/assets/{$file}\"/>";
        }
        return $html;
    }

    private function renderDevScript(string $file): string
    {
        $base = "http://localhost:{$this->assetDevServerPort}/assets";

        return <<<HTML
<script type="module" src="{$base}/@vite/client"></script>
<script src="{$base}/js/{$file}" type="module" defer></script>
HTML;
    }
}
