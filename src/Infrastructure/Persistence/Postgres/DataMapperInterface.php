<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Postgres;

interface DataMapperInterface
{
    /**
     * @param array<string, mixed> $row
     */
    public function toEntity(array $row): object;

    /**
     * @return array<string, mixed>
     */
    public function toRow(object $entity): array;
}
