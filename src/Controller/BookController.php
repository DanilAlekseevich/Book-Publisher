<?php

namespace App\Controller;

use App\Exception\BookCategoryNotFoundException;
use App\Service\BookService;
use HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    public function __construct(
        private readonly BookService $bookService
    )
    {
    }

    /**
     * @throws HttpException
     */
    #[Route(path: '/api/v1/category{id}/books')]
    public function booksByCategory(int $id): Response
    {
        try {
            return $this->json($this->bookService->getBookByCategory($id));
        } catch (BookCategoryNotFoundException $exception) {
            throw new HttpException($exception->getCode(), $exception->getMessage());
        }
    }
}
