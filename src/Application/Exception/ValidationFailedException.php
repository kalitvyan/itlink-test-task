<?php

declare(strict_types=1);

namespace App\Application\Exception;

final class ValidationFailedException extends ApplicationException
{
    /**
     * @param array<string, string[]> $fieldErrors attribute name => list of messages
     */
    public function __construct(private readonly array $fieldErrors)
    {
        parent::__construct(
            message: 'Validation failed.',
            errorCode: 'validation_failed',
            httpStatus: 422,
            title: 'Unprocessable Entity',
        );
    }

    public function getErrors(): array
    {
        $errors = [];

        foreach ($this->fieldErrors as $field => $messages) {
            foreach ($messages as $message) {
                $errors[] = [
                    'status' => (string) $this->getHttpStatus(),
                    'code' => $this->getErrorCode(),
                    'title' => $this->getTitle(),
                    'detail' => $message,
                    'source' => ['pointer' => '/data/attributes/' . $field],
                ];
            }
        }

        return $errors === [] ? parent::getErrors() : $errors;
    }
}
