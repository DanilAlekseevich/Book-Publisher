<?php

namespace App\Tests\Controller;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Tests\AbstractControllerTest;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookControllerTest extends AbstractControllerTest
{
    public function testBooksByCategory()
    {
        $categoryId = $this->createCategory();

        $this->client->request('GET', "/api/v1/category/$categoryId/books");
        $responseContent = $this->client->getResponse()->getContent();


        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatchesSchema($responseContent, [
            'type' => 'object',
            'require' => ['items'],
            'properties' => [
                'items' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'require' => ['id', 'slug', 'title', 'authors', 'image', 'meap', 'publicationDate'],
                        'properties' => [
                            'id' => ['type' => 'integer'],
                            'title' => ['type' => 'string'],
                            'slug' => ['type' => 'string'],
                            'image' => ['type' => 'string'],
                            'publicationDate' => ['type' => 'integer'],
                            'meap' => ['type' => 'boolean'],
                            'authors' => [
                                'type' => 'array',
                                'items' => ['type' => 'string']
                            ]
                        ]
                    ]
                ]
            ]
        ]);
    }

    private function createCategory(): int
    {
        $bookCategory = (new BookCategory())->setSlug('devices')->setTitle('Devices');
        $this->em->persist($bookCategory);

        $this->em->persist(
            (new Book())
            ->setTitle('PC')
            ->setSlug('pc')
            ->setImage('image')
            ->setMeap(true)
            ->setPublicationDate(new \DateTime())
            ->setAuthors(['Tester'])
            ->setCategories(new ArrayCollection([$bookCategory]))
        );

        $this->em->flush();

        return $bookCategory->getId();
    }
}
