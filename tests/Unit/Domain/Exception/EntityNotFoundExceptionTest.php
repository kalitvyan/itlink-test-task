<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Exception;

use App\Domain\Exception\EntityNotFoundException;
use Codeception\Test\Unit;

final class EntityNotFoundExceptionTest extends Unit
{
    public function testItRendersAsA404JsonApiError(): void
    {
        $exception = EntityNotFoundException::withId('Order', 42);

        $this->assertSame(404, $exception->getHttpStatus());
        $this->assertSame('resource_not_found', $exception->getErrorCode());
        $this->assertSame(
            [[
                'status' => '404',
                'code' => 'resource_not_found',
                'title' => 'Resource Not Found',
                'detail' => 'Order with id 42 was not found.',
            ]],
            $exception->getErrors(),
        );
    }
}
