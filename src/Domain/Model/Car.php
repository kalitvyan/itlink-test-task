<?php

declare(strict_types=1);

namespace App\Domain\Model;

use DateTimeImmutable;

final readonly class Car
{
    public function __construct(
        public ?int $id,
        public string $title,
        public string $description,
        public string $price,
        public string $photoUrl,
        public string $contacts,
        public DateTimeImmutable $createdAt,
        public ?CarOption $options = null,
    ) {
    }

    public function withId(int $id): self
    {
        return new self(
            id: $id,
            title: $this->title,
            description: $this->description,
            price: $this->price,
            photoUrl: $this->photoUrl,
            contacts: $this->contacts,
            createdAt: $this->createdAt,
            options: $this->options,
        );
    }

    public function withOptions(?CarOption $options): self
    {
        return new self(
            id: $this->id,
            title: $this->title,
            description: $this->description,
            price: $this->price,
            photoUrl: $this->photoUrl,
            contacts: $this->contacts,
            createdAt: $this->createdAt,
            options: $options,
        );
    }
}
