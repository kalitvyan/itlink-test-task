<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Model\Car;

interface CarRepositoryInterface
{
    public function save(Car $car): Car;

    public function findById(int $id): ?Car;

    /**
     * @return Car[]
     */
    public function findPage(int $page, int $pageSize): array;

    public function count(): int;
}
