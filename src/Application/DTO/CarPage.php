<?php

declare(strict_types=1);

namespace App\Application\DTO;

use App\Domain\Model\Car;

final readonly class CarPage
{
    /**
     * @param Car[] $items
     */
    public function __construct(
        public array $items,
        public int $page,
        public int $pageSize,
        public int $total,
    ) {
    }
}
