<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Application\DTO\CarPage;
use App\Domain\Repository\CarRepositoryInterface;

final class ListCarsService
{
    public function __construct(
        private readonly CarRepositoryInterface $carRepository,
    ) {
    }

    public function handle(int $page, int $pageSize): CarPage
    {
        return new CarPage(
            items: $this->carRepository->findPage($page, $pageSize),
            page: $page,
            pageSize: $pageSize,
            total: $this->carRepository->count(),
        );
    }
}
