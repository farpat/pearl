<?php

namespace App\Twig;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AssetExtension extends AbstractExtension
{
    private ParameterBagInterface $parameterBag;
    private ?array                $manifestJson = null;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('render_assets', [$this, 'renderAsset'], ['is_safe' => ['html']]),
        ];
    }

    public function renderAsset(string $asset)
    {
        $manifestPath = $this->parameterBag->get('kernel.project_dir') . '/public/assets/manifest.json';
        $hasManifestPath = file_exists($manifestPath);

        if ($hasManifestPath) {
            if ($this->manifestJson === null) {
                $this->manifestJson = json_decode(file_get_contents($manifestPath), true);
            }

            $asset = trim($asset, '/');
            $data = $this->manifestJson[$asset] ?? null;
            if ($data === null) {
                return '';
            }
            $jsFile = $data['file'];
            $cssFiles = $data['css'] ?? [];

            $html = <<<HTML
          <script src="/assets/{$jsFile}" type="module" defer></script>
HTML;
            foreach ($cssFiles as $css) {
                $html .= <<<HTML
              <link rel="stylesheet" href="/assets/{$css}" media="screen"/>
            HTML;
            }

            return $html;
        } else {
            $base = 'http://localhost:3000/assets';

            return <<<HTML
<script type="module" src="{$base}/@vite/client"></script>

<script type="module">
    import RefreshRuntime from "{$base}/@react-refresh"
    RefreshRuntime.injectIntoGlobalHook(window)
    window.\$RefreshReg\$ = () => {}
    window.\$RefreshSig\$ = () => (type) => type
    window.__vite_plugin_react_preamble_installed__ = true
</script>

<script src="{$base}{$asset}" type="module" defer></script>
HTML;
        }
    }
}
