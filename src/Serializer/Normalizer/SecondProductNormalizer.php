<?php

namespace App\Serializer\Normalizer;

use App\Entity\Product;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class SecondProductNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private ObjectNormalizer $normalizer;

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
            'name'          => $object->getName(),
            'picture_url'   => $object->getPictureUrl(),
            'price'         => $object->getPrice(),
            'category_name' => $object->getCategory()->getName()
        ];
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof Product && $format === 'second-normalizer';
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
