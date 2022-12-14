<?php

namespace App\Controller;

use App\Model\BookCategoryListResponse;
use App\Service\BookCategoryService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

class BookCategoryController extends AbstractController
{
    public function __construct(private readonly BookCategoryService $bookCategoryService)
    {
    }


    #[Route('/api/v1/book/categories', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Return book categories',
        content: new OA\JsonContent(ref: new Model(type: BookCategoryListResponse::class))
    )]
    public function getCategories(): JsonResponse
    {
        return $this->json($this->bookCategoryService->getCategories());
    }
}
