<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Response;

final class JsonApiResponse
{
    /**
     * @param array<string, mixed> $meta
     * @return array<string, mixed>
     */
    public static function data(mixed $data, array $meta = []): array
    {
        $document = ['data' => $data];

        if ($meta !== []) {
            $document['meta'] = $meta;
        }

        return $document;
    }
}
