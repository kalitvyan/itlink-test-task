<?php

declare(strict_types=1);

namespace App\Domain\Model;

final class CarOption
{
    public function __construct(
        public readonly string $brand,
        public readonly string $model,
        public readonly int $year,
        public readonly string $body,
        public readonly int $mileage,
    ) {
    }
}
