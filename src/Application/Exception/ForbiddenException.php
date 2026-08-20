<?php

declare(strict_types=1);

namespace App\Application\Exception;

final class ForbiddenException extends ApplicationException
{
    public function __construct(
        string $message = 'You are not allowed to perform this action.'
    ) {
        parent::__construct(
            message: $message,
            errorCode: 'forbidden',
            httpStatus: 403,
            title: 'Forbidden',
        );
    }
}
