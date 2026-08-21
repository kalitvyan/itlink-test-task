<?php

declare(strict_types=1);

namespace App\Domain\Model;

final readonly class CarOption
{
    public function __construct(
        public string $brand,
        public string $model,
        public int $year,
        public string $body,
        public int $mileage,
    ) {
    }
}
