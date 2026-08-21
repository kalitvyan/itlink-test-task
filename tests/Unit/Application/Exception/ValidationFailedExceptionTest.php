<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Exception;

use App\Application\Exception\ValidationFailedException;
use Codeception\Test\Unit;

final class ValidationFailedExceptionTest extends Unit
{
    public function testItRendersOneErrorPerFieldMessageWithASourcePointer(): void
    {
        $exception = new ValidationFailedException([
            'price' => ['Price must be no less than 0.'],
            'options/mileage' => ['Mileage cannot be blank.'],
        ]);

        $this->assertSame(422, $exception->getHttpStatus());
        $this->assertSame(
            [
                [
                    'status' => '422',
                    'code' => 'validation_failed',
                    'title' => 'Unprocessable Entity',
                    'detail' => 'Price must be no less than 0.',
                    'source' => ['pointer' => '/data/attributes/price'],
                ],
                [
                    'status' => '422',
                    'code' => 'validation_failed',
                    'title' => 'Unprocessable Entity',
                    'detail' => 'Mileage cannot be blank.',
                    'source' => ['pointer' => '/data/attributes/options/mileage'],
                ],
            ],
            $exception->getErrors(),
        );
    }
}
