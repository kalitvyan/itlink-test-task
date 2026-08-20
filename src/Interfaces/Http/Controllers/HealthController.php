<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Controllers;

use Yii;

final class HealthController extends ApiController
{
    /**
     * @return array<string, mixed>
     */
    public function actionIndex(): array
    {
        return $this->success(['status' => 'ok']);
    }

    /**
     * @return array<string, mixed>
     */
    public function actionReady(): array
    {
        Yii::$app->db->createCommand('SELECT 1')->queryScalar();

        return $this->success(['status' => 'ready']);
    }
}
