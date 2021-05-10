<?php

namespace App\Service\Asset;

interface AssetInterface
{
    /**
     * AssetInterface constructor.
     * @param string $manifestJsonPath Chemin vers le fichier " manifest.json "
     * @param bool $isLegacyBrowser Indique si la requête courante provient d'un navigateur " legacy " ou non
     * @param int $assetDevServerPort Port du serveur de rendu d'asset (utile uniquement en mode développement)
     */
    public function __construct(string $manifestJsonPath, bool $isLegacyBrowser, int $assetDevServerPort);

    /**
     * Rend une balise HTML à partir d'une entrée (<link> ou <script>)
     * @param string $entry
     * @return string
     */
    public function renderAsset(string $entry): string;
}