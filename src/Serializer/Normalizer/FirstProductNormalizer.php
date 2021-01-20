<?php

namespace App\Serializer\Normalizer;

use App\Entity\Product;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class FirstProductNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private $normalizer;

    public function __construct(ObjectNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    /**
     * @param Product $object
     */
    public function normalize($object, $format = null, array $context = []): array
    {
        return [
            'name'        => $object->getName(),
            'description' => $object->getDescription(),
            'picture_url' => $object->getPictureUrl(),
            'price'       => $object->getPrice()
        ];
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof \App\Entity\Product && $format === 'first-normalizer';
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
