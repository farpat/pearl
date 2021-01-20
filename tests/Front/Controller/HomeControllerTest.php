<?php

namespace App\Tests\Front\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function test_homepage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h1', 'Welcome!');

        $links = $crawler->filter('.menu a');
        $this->assertEquals('Product dashboard', $links->eq(0)->text());
        $this->assertEquals('First comparator', $links->eq(1)->text());
        $this->assertEquals('Second comparator', $links->eq(2)->text());
    }
}
