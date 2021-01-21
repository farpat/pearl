<?php

namespace App\Tests\Front\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Serializer\Encoder\CsvEncoder;

class ProductFlowControllerTest extends WebTestCase
{
    public function test_first_comparator()
    {
        $client = static::createClient();
        $client->request('GET', '/feed/get/my-first-comparator');

        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
        $csvContentInString = $response->getContent();
        $csvData = (new CsvEncoder())->decode($csvContentInString, 'csv');
        $products = self::$container->get(ProductRepository::class)->findAll();
        $this->assertCount(count($products), $csvData);
        $i = 0;
        foreach ($products as $product) {
            $this->assertEquals($product->getName(), $csvData[$i]['name']);
            $this->assertEquals($product->getDescription(), $csvData[$i]['description']);
            $this->assertEquals($product->getPictureUrl(), $csvData[$i]['picture_url']);
            $this->assertEquals($product->getPrice(), $csvData[$i]['price']);
            $i++;
        }
    }

    public function test_second_comparator()
    {
        $client = static::createClient();
        $client->request('GET', '/feed/get/my-second-comparator');

        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
        $csvContentInString = $response->getContent();
        $csvData = (new CsvEncoder())->decode($csvContentInString, 'csv');
        $products = self::$container->get(ProductRepository::class)->findProductGreatherThan(20);
        $this->assertCount(count($products), $csvData);
        $i = 0;
        foreach ($products as $product) {
            $this->assertEquals($product->getName(), $csvData[$i]['name']);
            $this->assertEquals($product->getPictureUrl(), $csvData[$i]['picture_url']);
            $this->assertEquals($product->getPrice(), $csvData[$i]['price']);
            $this->assertEquals($product->getCategory()->getName(), $csvData[$i]['category_name']);
            $i++;
        }
    }
}
