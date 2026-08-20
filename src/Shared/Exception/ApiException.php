<?php

declare(strict_types=1);

namespace App\Shared\Exception;

use RuntimeException;
use Throwable;

abstract class ApiException extends RuntimeException implements ApiExceptionInterface
{
    public function __construct(
        string $message,
        private readonly string $errorCode,
        private readonly int $httpStatus,
        private readonly string $title,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, 0, $previous);
    }

    public function getHttpStatus(): int
    {
        return $this->httpStatus;
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getErrors(): array
    {
        return [
            [
                'status' => (string) $this->httpStatus,
                'code' => $this->errorCode,
                'title' => $this->title,
                'detail' => $this->getMessage(),
            ],
        ];
    }
}
