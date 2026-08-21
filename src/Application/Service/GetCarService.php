<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Model\Car;
use App\Domain\Repository\CarRepositoryInterface;

final class GetCarService
{
    public function __construct(
        private readonly CarRepositoryInterface $carRepository,
    ) {
    }

    public function handle(int $id): Car
    {
        return $this->carRepository
            ->findById($id) ?? throw EntityNotFoundException::withId('Car', $id);
    }
}
