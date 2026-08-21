<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Postgres;

use App\Domain\Model\Car;
use App\Domain\Model\CarOption;
use DateTimeImmutable;
use InvalidArgumentException;

final class CarDataMapper implements DataMapperInterface
{
    public function toEntity(array $row): object
    {
        return new Car(
            id: (int) $row['id'],
            title: (string) $row['title'],
            description: (string) $row['description'],
            price: (string) $row['price'],
            photoUrl: (string) $row['photo_url'],
            contacts: (string) $row['contacts'],
            createdAt: new DateTimeImmutable((string) $row['created_at']),
        );
    }

    public function toRow(object $entity): array
    {
        if (!$entity instanceof Car) {
            throw new InvalidArgumentException(
                sprintf('%s expects %s, got %s.', self::class, Car::class, $entity::class)
            );
        }

        return [
            'title' => $entity->title,
            'description' => $entity->description,
            'price' => $entity->price,
            'photo_url' => $entity->photoUrl,
            'contacts' => $entity->contacts,
            'created_at' => $entity->createdAt->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * @param array<string, mixed> $row
     */
    public function optionsToEntity(array $row): CarOption
    {
        return new CarOption(
            brand: (string) $row['brand'],
            model: (string) $row['model'],
            year: (int) $row['year'],
            body: (string) $row['body'],
            mileage: (int) $row['mileage'],
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function optionsToRow(int $carId, CarOption $options): array
    {
        return [
            'car_id' => $carId,
            'brand' => $options->brand,
            'model' => $options->model,
            'year' => $options->year,
            'body' => $options->body,
            'mileage' => $options->mileage,
        ];
    }
}
