<?php

declare(strict_types=1);

namespace App\Interfaces\Http\ErrorHandler;

use App\Shared\Exception\ApiExceptionInterface;
use Throwable;
use Yii;
use yii\web\ErrorHandler;
use yii\web\HttpException;

final class ApiErrorHandler extends ErrorHandler
{
    /**
     * @return array{
     *      errors: array<int, array{
     *          status: string,
     *          code: string,
     *          title: string,
     *          detail: string,
     *      }>,
     *  }
     */
    protected function convertExceptionToArray($exception): array
    {
        $status = $this->resolveStatusCode($exception);

        Yii::$app->getResponse()->setStatusCode($status);

        return [
            'errors' => $this->resolveErrors($exception, $status),
        ];
    }

    private function resolveStatusCode(Throwable $exception): int
    {
        return match (true) {
            $exception instanceof ApiExceptionInterface => $exception->getHttpStatus(),
            $exception instanceof HttpException => $exception->statusCode,
            default => 500,
        };
    }

    /**
     * @return array<int, array{
     *      status: string,
     *      code: string,
     *      title: string,
     *      detail: string,
     * }>
     */
    private function resolveErrors(Throwable $exception, int $status): array
    {
        if ($exception instanceof ApiExceptionInterface) {
            return $exception->getErrors();
        }

        if ($exception instanceof HttpException) {
            $detail = $exception->getMessage() !== ''
                ? $exception->getMessage()
                : $exception->getName();

            return [[
                'status' => (string) $status,
                'code' => 'http_' . $status,
                'title' => $exception->getName(),
                'detail' => $detail,
            ]];
        }

        return [[
            'status' => (string) $status,
            'code' => 'internal_server_error',
            'title' => 'Internal Server Error',
            'detail' => YII_DEBUG
                ? $exception->getMessage()
                : 'An internal server error occurred.',
        ]];
    }
}
