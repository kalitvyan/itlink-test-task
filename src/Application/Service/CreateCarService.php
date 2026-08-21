<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Application\DTO\CreateCarCommand;
use App\Domain\Model\Car;
use App\Domain\Repository\CarRepositoryInterface;
use DateTimeImmutable;

final class CreateCarService
{
    public function __construct(
        private readonly CarRepositoryInterface $carRepository,
    ) {
    }

    public function handle(CreateCarCommand $command): Car
    {
        $car = new Car(
            id: null,
            title: $command->title,
            description: $command->description,
            price: $command->price,
            photoUrl: $command->photoUrl,
            contacts: $command->contacts,
            createdAt: new DateTimeImmutable(),
            options: $command->options,
        );

        return $this->carRepository->save($car);
    }
}
