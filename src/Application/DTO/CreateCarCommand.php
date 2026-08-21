<?php

declare(strict_types=1);

namespace App\Application\DTO;

use App\Domain\Model\CarOption;

final class CreateCarCommand
{
    public function __construct(
        public readonly string $title,
        public readonly string $description,
        public readonly string $price,
        public readonly string $photoUrl,
        public readonly string $contacts,
        public readonly ?CarOption $options,
    ) {
    }
}
