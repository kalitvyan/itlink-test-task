<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Controllers;

use App\Application\Service\CreateCarService;
use App\Application\Service\GetCarService;
use App\Application\Service\ListCarsService;
use App\Domain\Model\Car;
use App\Interfaces\Http\Requests\CreateCarRequest;
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

        return $this->success($this->present($car));
    }

    /**
     * @return array<string, mixed>
     */
    public function actionView(int $id): array
    {
        return $this->success($this->present($this->getCarService->handle($id)));
    }

    /**
     * @return array<string, mixed>
     */
    public function actionList(): array
    {
        $page = max(1, (int) Yii::$app->request->get('page', 1));

        $result = $this->listCarsService->handle($page, self::DEFAULT_PAGE_SIZE);

        return $this->success(
            array_map($this->present(...), $result->items),
            [
                'page' => $result->page,
                'pageSize' => $result->pageSize,
                'totalCount' => $result->total,
                'totalPages' => (int) ceil($result->total / $result->pageSize),
            ],
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function present(Car $car): array
    {
        return [
            'id' => $car->id,
            'title' => $car->title,
            'description' => $car->description,
            'price' => $car->price,
            'photo_url' => $car->photoUrl,
            'contacts' => $car->contacts,
            'created_at' => $car->createdAt->format(DATE_ATOM),
            'options' => $car->options === null ? null : [
                'brand' => $car->options->brand,
                'model' => $car->options->model,
                'year' => $car->options->year,
                'body' => $car->options->body,
                'mileage' => $car->options->mileage,
            ],
        ];
    }
}
