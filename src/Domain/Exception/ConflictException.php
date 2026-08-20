<?php

declare(strict_types=1);

namespace App\Domain\Exception;

class ConflictException extends DomainException
{
    public function __construct(string $message)
    {
        parent::__construct(
            message: $message,
            errorCode: 'conflict',
            httpStatus: 409,
            title: 'Conflict',
        );
    }
}
