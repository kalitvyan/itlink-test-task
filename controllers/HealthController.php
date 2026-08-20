<?php

declare(strict_types=1);

namespace app\controllers;

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
}
