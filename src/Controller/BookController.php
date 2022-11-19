<?php

namespace App\Controller;

use App\Model\BookCategoryListResponse;
use App\Model\ErrorResponse;
use App\Service\BookService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

class BookController extends AbstractController
{
    public function __construct(
        private readonly BookService $bookService
    ) {
    }

    #[Route(path: '/api/v1/category/{id}/books', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Return books by category id',
        content: new OA\JsonContent(ref: new Model(type: BookCategoryListResponse::class))
    )]
    #[OA\Response(
        response: 404,
        description: 'Book category not found',
        content: new OA\JsonContent(ref: new Model(type: ErrorResponse::class))
    )]
    public function booksByCategory(int $id): Response
    {
        return $this->json($this->bookService->getBookByCategory($id));
    }
}
