<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Service;

use App\Application\DTO\CreateCarCommand;
use App\Application\Service\CreateCarService;
use App\Domain\Model\Car;
use App\Domain\Model\CarOption;
use App\Domain\Repository\CarRepositoryInterface;
use Codeception\Test\Unit;
use DateTimeImmutable;

final class CreateCarServiceTest extends Unit
{
    public function testItBuildsACarFromTheCommandAndPersistsItThroughTheRepository(): void
    {
        $command = new CreateCarCommand(
            title: 'Audi A6',
            description: 'One owner, full service history',
            price: '28000.00',
            photoUrl: 'https://picsum.photos/seed/audi-a6/800/600',
            contacts: '+1234567890',
            options: new CarOption(
                brand: 'Audi',
                model: 'A6',
                year: 2021,
                body: 'Sedan',
                mileage: 25000,
            ),
        );

        $persistedCar = new Car(
            id: 1,
            title: $command->title,
            description: $command->description,
            price: $command->price,
            photoUrl: $command->photoUrl,
            contacts: $command->contacts,
            createdAt: new DateTimeImmutable(),
            options: $command->options,
        );

        $repository = $this->createMock(CarRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Car $car) use ($command): bool {
                return $car->id === null
                    && $car->title === $command->title
                    && $car->description === $command->description
                    && $car->price === $command->price
                    && $car->photoUrl === $command->photoUrl
                    && $car->contacts === $command->contacts
                    && $car->options === $command->options;
            }))
            ->willReturn($persistedCar);

        $service = new CreateCarService($repository);

        $result = $service->handle($command);

        $this->assertSame($persistedCar, $result);
        $this->assertSame(1, $result->id);
    }
}
