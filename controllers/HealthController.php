<?php

declare(strict_types=1);

namespace app\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\Response;

class HealthController extends Controller
{
    public function actionIndex(): array
    {
        return [
            'status' => 'ok',
        ];
    }

    public function actionReady(): array
    {
        Yii::$app->db->createCommand('SELECT 1')->queryScalar();

        return [
            'status' => 'ready',
        ];
    }
}
