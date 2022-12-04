<?php

namespace App\Tests\Service;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Exception\RequestBodyConvertException;
use App\Model\BookListItem;
use App\Model\BookListResponse;
use App\Repository\BookCategoryRepository;
use App\Repository\BookRepository;
use App\Service\BookService;
use App\Tests\AbstractTestCase;
use Doctrine\Common\Collections\ArrayCollection;

class BookServiceTest extends AbstractTestCase
{
    public function testGetBookByCategoryNotFound(): void
    {
        $bookRepository = $this->createMock(BookRepository::class);
        $bookCategoryRepository = $this->createMock(BookCategoryRepository::class);
        $bookCategoryRepository->expects($this->once())
            ->method('existsById')
            ->with(130)
            ->willReturn(false);

        $this->expectException(RequestBodyConvertException::class);

        (new BookService($bookRepository, $bookCategoryRepository))->getBookByCategory(130);
    }

    public function testGetBookByCategory(): void
    {
        $bookRepository = $this->createMock(BookRepository::class);
        $bookRepository->expects($this->once())
            ->method('findBookByCategoryId')
            ->with(130)
            ->willReturn([$this->createBook()]);

        $bookCategoryRepository = $this->createMock(BookCategoryRepository::class);
        $bookCategoryRepository->expects($this->once())
            ->method('existsById')
            ->with(130)
            ->willReturn(true);

        $service = new BookService($bookRepository, $bookCategoryRepository);
        $expected = new BookListResponse([$this->createBookItemModel()]);
        $this->assertEquals($expected, $service->getBookByCategory(130));
    }

    public function createBook(): Book
    {
        $book = (new Book())
            ->setTitle('Test book')
            ->setSlug('test-book')
            ->setMeap(false)
            ->setImage('')
            ->setAuthors(['Jesus'])
            ->setCategories(new ArrayCollection())
            ->setPublicationDate(new \DateTime('2020-10-10'));
        $this->setEntityId($book, 123);

        return $book;
    }

    public function createBookItemModel(): BookListItem
    {
        return (new BookListItem())
            ->setId(123)
            ->setTitle('Test book')
            ->setSlug('test-book')
            ->setMeap(false)
            ->setImage('')
            ->setAuthors(['Jesus'])
            ->setPublicationDate(1602288000);
    }
}
