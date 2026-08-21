<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Response;

use App\Domain\Model\Car;

final class CarResponse
{
    /**
     * @return array<string, mixed>
     */
    public static function fromCar(Car $car): array
    {
        return [
            'id' => $car->id,
            'title' => $car->title,
            'description' => $car->description,
            'price' => $car->price,
            'photo_url' => $car->photoUrl,
            'contacts' => $car->contacts,
            'created_at' => $car->createdAt->format(DATE_ATOM),
            'options' => $car->options === null ? null : [
                'brand' => $car->options->brand,
                'model' => $car->options->model,
                'year' => $car->options->year,
                'body' => $car->options->body,
                'mileage' => $car->options->mileage,
            ],
        ];
    }
}
