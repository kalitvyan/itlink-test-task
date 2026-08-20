<?php

declare(strict_types=1);

namespace App\Domain\Exception;

final class EntityNotFoundException extends DomainException
{
    public static function withId(string $entityName, string|int $id): self
    {
        $message = "$entityName with id $id was not found.";

        return new self($message);
    }

    public function __construct(string $message)
    {
        parent::__construct(
            message: $message,
            errorCode: 'resource_not_found',
            httpStatus: 404,
            title: 'Resource Not Found',
        );
    }
}
