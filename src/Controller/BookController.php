<?php

namespace App\Controller;

use App\Model\BookCategoryListResponse;
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

    #[Route(path: '/api/v1/category/{id}/books')]
    #[OA\Response(
        response: 200,
        description: 'Return books by category id',
        content: new OA\JsonContent(ref: new Model(type: BookCategoryListResponse::class))
    )]
    public function booksByCategory(int $id): Response
    {
        return $this->json($this->bookService->getBookByCategory($id));
    }
}
