<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookControllerTest extends WebTestCase
{
    public function testBooksByCategory()
    {
        $client = static::createClient();
        $client->request('GET', '/api/v1/category/4/books');
        $responseContent = $client->getResponse()->getContent();


        $this->assertResponseIsSuccessful();
        $this->assertJsonStringEqualsJsonFile(
            __DIR__ . '/response/BookControllerTest_testBooksByCategory.json',
            $responseContent
        );
    }
}
