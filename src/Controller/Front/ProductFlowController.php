<?php

namespace App\Controller\Front;

use App\Repository\ProductRepository;
use App\Service\ProductFlowService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/feed/get", name="front_product_flow_")
 */
class ProductFlowController extends AbstractController
{
    private ProductFlowService $productFlowService;

    public function __construct(ProductFlowService $productFlowService)
    {
        $this->productFlowService = $productFlowService;
    }

    /**
     * @Route("/my-first-comparator", name="first_comparator", methods={"GET"})
     */
    public function firstComparator(): Response
    {
        return new Response($this->productFlowService->getFirstComparator(), 200, [
            'Content-Type' => 'text/csv'
        ]);
    }

    /**
     * @Route("/my-second-comparator", name="second_comparator", methods={"GET"})
     */
    public function secondComparator(SerializerInterface $serializer, ProductRepository $productRepository): Response
    {
        return new Response($this->productFlowService->getSecondComparator(), 200, [
            'Content-Type' => 'text/csv'
        ]);
    }
}
