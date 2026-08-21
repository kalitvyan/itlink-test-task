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
            'email' => ['Email cannot be blank.', 'Email is invalid.'],
            'age' => ['Age must be an integer.'],
        ]);

        $this->assertSame(422, $exception->getHttpStatus());
        $this->assertSame(
            [
                [
                    'status' => '422',
                    'code' => 'validation_failed',
                    'title' => 'Unprocessable Entity',
                    'detail' => 'Email cannot be blank.',
                    'source' => ['pointer' => '/data/attributes/email'],
                ],
                [
                    'status' => '422',
                    'code' => 'validation_failed',
                    'title' => 'Unprocessable Entity',
                    'detail' => 'Email is invalid.',
                    'source' => ['pointer' => '/data/attributes/email'],
                ],
                [
                    'status' => '422',
                    'code' => 'validation_failed',
                    'title' => 'Unprocessable Entity',
                    'detail' => 'Age must be an integer.',
                    'source' => ['pointer' => '/data/attributes/age'],
                ],
            ],
            $exception->getErrors(),
        );
    }
}
