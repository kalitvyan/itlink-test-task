<?php

declare(strict_types=1);

namespace App\Application\Exception;

final class UnauthorizedException extends ApplicationException
{
    public function __construct(string $message = 'Authentication is required.')
    {
        parent::__construct(
            message: $message,
            errorCode: 'unauthorized',
            httpStatus: 401,
            title: 'Unauthorized',
        );
    }
}
