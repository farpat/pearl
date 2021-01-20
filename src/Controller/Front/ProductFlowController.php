<?php

namespace App\Controller\Front;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/feed/get", name="front_product_flow_")
 */
class ProductFlowController extends AbstractController
{
    /**
     * @Route("/my-first-comparator", name="first_comparator", methods={"GET"})
     */
    public function firstComparator(ProductRepository $productRepository, SerializerInterface $serializer): Response
    {
        $dataToEncode = $serializer->normalize($productRepository->findAll(), 'first-normalizer');
        return new Response($serializer->serialize($dataToEncode, 'csv'), 200, [
            'Content-Type' => 'text/csv'
        ]);
    }

    /**
     * @Route("/my-second-comparator", name="second_comparator", methods={"GET"})
     */
    public function secondComparator(SerializerInterface $serializer, ProductRepository $productRepository): Response
    {
        $dataToEncode = $serializer->normalize($productRepository->findProductGreatherThan(), 'first-normalizer');
        return new Response($serializer->serialize($dataToEncode, 'csv'), 200, [
            'Content-Type' => 'text/csv'
        ]);
    }
}
