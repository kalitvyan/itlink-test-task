<?php

declare(strict_types=1);

namespace App\Domain\Model;

use DateTimeImmutable;

final class Car
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $title,
        public readonly string $description,
        public readonly string $price,
        public readonly string $photoUrl,
        public readonly string $contacts,
        public readonly DateTimeImmutable $createdAt,
        public readonly ?CarOption $options = null,
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
