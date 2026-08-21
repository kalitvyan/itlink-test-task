<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Controllers;

use App\Application\Service\CreateCarService;
use App\Application\Service\GetCarService;
use App\Application\Service\ListCarsService;
use App\Interfaces\Http\Requests\CreateCarRequest;
use App\Interfaces\Http\Response\CarResponse;
use Yii;

final class CarController extends ApiController
{
    private const int DEFAULT_PAGE_SIZE = 20;

    public function __construct(
        string $id,
        $module,
        private readonly CreateCarService $createCarService,
        private readonly GetCarService $getCarService,
        private readonly ListCarsService $listCarsService,
        array $config = [],
    ) {
        parent::__construct($id, $module, $config);
    }

    /**
     * @return array<string, mixed>
     */
    public function actionCreate(): array
    {
        $request = new CreateCarRequest();
        $request->load(Yii::$app->request->bodyParams, '');
        $this->assertValid($request);

        $car = $this->createCarService->handle($request->toCommand());

        Yii::$app->response->statusCode = 201;

        return $this->success(CarResponse::fromCar($car));
    }

    /**
     * @return array<string, mixed>
     */
    public function actionView(int $id): array
    {
        return $this->success(CarResponse::fromCar($this->getCarService->handle($id)));
    }

    /**
     * @return array<string, mixed>
     */
    public function actionList(): array
    {
        $page = max(1, (int) Yii::$app->request->get('page', 1));

        $result = $this->listCarsService->handle($page, self::DEFAULT_PAGE_SIZE);

        return $this->success(
            array_map(CarResponse::fromCar(...), $result->items),
            [
                'page' => $result->page,
                'pageSize' => $result->pageSize,
                'totalCount' => $result->total,
                'totalPages' => (int) ceil($result->total / $result->pageSize),
            ],
        );
    }
}
