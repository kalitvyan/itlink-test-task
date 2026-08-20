<?php

declare(strict_types=1);

namespace App\Shared\Exception;

use Throwable;

interface ApiExceptionInterface extends Throwable
{
    public function getHttpStatus(): int;

    public function getErrorCode(): string;

    public function getTitle(): string;

    /**
     * @return array<int, array{
     *      status: string,
     *      code: string,
     *      title: string,
     *      detail: string,
     *      source?: array{pointer: string}
     * }>
     */
    public function getErrors(): array;
}
