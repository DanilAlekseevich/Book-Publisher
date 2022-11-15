<?php

declare(strict_types=1);

namespace App\Model;

class ErrorResponse
{
    public function __construct(private string $message)
    {
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
