<?php

declare(strict_types=1);

namespace App\Application\DTO;

use App\Domain\Model\CarOption;

final readonly class CreateCarCommand
{
    public function __construct(
        public string $title,
        public string $description,
        public string $price,
        public string $photoUrl,
        public string $contacts,
        public ?CarOption $options,
    ) {
    }
}
