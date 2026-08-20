<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Controllers;

use App\Interfaces\Http\Response\JsonApiResponse;
use yii\rest\Controller;

abstract class ApiController extends Controller
{
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        unset($behaviors['authenticator'], $behaviors['rateLimiter']);

        return $behaviors;
    }

    /**
     * @param array<string, mixed> $meta
     * @return array<string, mixed>
     */
    protected function success(mixed $data, array $meta = []): array
    {
        return JsonApiResponse::data($data, $meta);
    }
}
