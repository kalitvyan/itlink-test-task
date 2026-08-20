<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use App\Shared\Exception\ApiException;

abstract class DomainException extends ApiException
{
}
