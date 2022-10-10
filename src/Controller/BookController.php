<?php

namespace App\Controller;

use App\Exception\BookCategoryNotFoundException;
use App\Model\BookCategoryListResponse;
use App\Service\BookService;
use HttpException;
use Nelmio\ApiDocBundle\Model\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

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
    #[Route(path: '/api/v1/category/{id}/books')]
    #[OA\Response(
        response: 200,
        description: 'Return books by category id',
        content: new Model(type: BookCategoryListResponse::class)
    )]
    public function booksByCategory(int $id): Response
    {
        try {
            return $this->json($this->bookService->getBookByCategory($id));
        } catch (BookCategoryNotFoundException $exception) {
            throw new HttpException($exception->getCode(), $exception->getMessage());
        }
    }
}
