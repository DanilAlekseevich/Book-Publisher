<?php

declare(strict_types=1);

namespace App\Listener;

use App\Model\ErrorResponse;
use App\Service\ExceptionHandler\ExceptionMapping;
use App\Service\ExceptionHandler\ExceptionMappingResolver;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class ApiExceptionListener
{
    public function __construct(
        private readonly ExceptionMappingResolver $resolver,
        private readonly LoggerInterface $logger,
        private readonly SerializerInterface $serializer
    ) {
    }

    public function __invoke(ExceptionEvent $event): JsonResponse
    {
        $throwable = $event->getThrowable();
        $mapping = $this->resolver->resolve((string)$throwable);
        if (!$mapping) {
            $mapping = ExceptionMapping::fromCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        if ($mapping->getCode() >= Response::HTTP_INTERNAL_SERVER_ERROR || $mapping->isLoggable()) {
            $this->logger->error($throwable->getMessage(), [
                'trace' => $throwable->getTraceAsString(),
                'previous' => $throwable->getPrevious()->getMessage() ?? ''
            ]);
        }

        $message = $mapping->isHidden()
            ? Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR]
            : $throwable->getMessage();

        $data = $this->serializer->serialize(new ErrorResponse($message), JsonEncoder::FORMAT);

        return new JsonResponse($data, $mapping->getCode(), [], true);
    }
}
