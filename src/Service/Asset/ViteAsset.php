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

            [
                'script' => $script,
                'cssFiles' => $cssFiles,
                'importFiles' => $importFiles,
                'polyfillFile' => $polyfillFile
            ] = $this->getData($entry);

            $html = '';
            foreach ($dependencies as $dependency) {
                $html .= AssetInterface::DEPENDENCIES[$dependency] ?? '';
            }

            $html .= $this->renderProductionImports($importFiles);
            $html .= $this->renderProductionScript($script, $polyfillFile);
            $html .= $this->renderProductionStyles($cssFiles);

            return $html;
        }

        return $this->renderDevScript($entry);
    }

    /**
     * @return array{script: string, cssFiles: string[], importFiles: string[], polyfillFile: ?string}
     * @throws AssetException
     */
    private function getData(string $asset): array
    {
        $key = $this->isLegacyBrowser ?
            'js/' . str_replace('.js', '-legacy.js', $asset) :
            'js/' . $asset;

        $data = $this->manifestJson[$key] ?? null;
        if ($data === null) {
            throw new AssetException("L'entrée << $key >> n'existe pas !");
        }

        $imports = [];
        foreach($data['imports'] ?? [] as $importKey) {
            $imports[] = $this->manifestJson[$importKey]['file'];
        }

        return [
            'script'      => $data['file'],
            'cssFiles'    => $data['css'] ?? [],
            'importFiles' => $imports,
            'polyfillFile' => $this->isLegacyBrowser ? $this->manifestJson['../vite/legacy-polyfills']['file'] : null,
        ];
    }

    private function renderProductionScript(string $file, ?string $polyfillFile): string
    {
        return $polyfillFile ?
            "<script src=\"/assets/{$polyfillFile}\"></script>" .
            "<script type=\"systemjs-module\" src=\"/assets/{$file}\" defer></script>" :
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
<script src="{$base}/js/{$file}" type="module"></script>
HTML;
    }
}
