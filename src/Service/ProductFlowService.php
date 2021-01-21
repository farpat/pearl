<?php

namespace App\Service;


use App\Repository\ProductRepository;
use Symfony\Component\Serializer\SerializerInterface;

class ProductFlowService
{
    private ProductRepository   $productRepository;
    private SerializerInterface $serializer;

    public function __construct(ProductRepository $productRepository, SerializerInterface $serializer)
    {
        $this->productRepository = $productRepository;
        $this->serializer = $serializer;
    }

    public function getFirstComparator(): string
    {
        return $this->returnSeralizedData(
            $this->serializer->normalize(
                $this->productRepository->findAll(),
                'first-normalizer'
            )
        );
    }

    private function returnSeralizedData(array $data): string
    {
        return $this->serializer->serialize($data, 'csv');
    }

    public function getSecondComparator(float $price = 20): string
    {
        return $this->returnSeralizedData(
            $this->serializer->normalize(
                $this->productRepository->findProductGreatherThan($price),
                'second-normalizer'
            )
        );
    }
}