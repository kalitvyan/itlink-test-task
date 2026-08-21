<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Postgres;

use App\Domain\Model\Car;
use App\Domain\Repository\CarRepositoryInterface;
use yii\db\Connection;
use yii\db\Query;

final class CarRepository extends PostgresRepository implements CarRepositoryInterface
{
    public function __construct(
        Connection $db,
        private readonly CarDataMapper $carMapper,
    ) {
        parent::__construct($db, $carMapper);
    }

    protected function tableName(): string
    {
        return 'car';
    }

    public function save(Car $car): Car
    {
        return $this->db->transaction(function () use ($car): Car {
            $this->insertRow($car);

            $id = (int) $this->db->getLastInsertID();
            $car = $car->withId($id);

            if ($car->options !== null) {
                $this->db->createCommand()
                    ->insert('car_option', $this->carMapper->optionsToRow($id, $car->options))
                    ->execute();
            }

            return $car;
        });
    }

    public function findById(int $id): ?Car
    {
        $car = $this->findOneRow(['id' => $id]);

        if ($car === null) {
            return null;
        }

        /** @var Car $car */
        return $this->attachOptions($car);
    }

    public function findPage(int $page, int $pageSize): array
    {
        $rows = $this->query()
            ->orderBy(['id' => SORT_DESC])
            ->limit($pageSize)
            ->offset(($page - 1) * $pageSize)
            ->all($this->db);

        if ($rows === []) {
            return [];
        }

        /** @var Car[] $cars */
        $cars = array_map($this->carMapper->toEntity(...), $rows);
        $carIds = array_map(static fn (Car $car): int => (int) $car->id, $cars);

        $optionRows = (new Query())
            ->from('car_option')
            ->where(['car_id' => $carIds])
            ->all($this->db);

        $optionsByCarId = [];
        foreach ($optionRows as $optionRow) {
            $optionsByCarId[(int) $optionRow['car_id']] = $this->carMapper->optionsToEntity($optionRow);
        }

        return array_map(
            static fn (Car $car): Car => $car->withOptions($optionsByCarId[$car->id] ?? null),
            $cars,
        );
    }

    public function count(): int
    {
        return (int) $this->query()->count('*', $this->db);
    }

    private function attachOptions(Car $car): Car
    {
        $optionRow = (new Query())
            ->from('car_option')
            ->where(['car_id' => $car->id])
            ->one($this->db);

        return $car->withOptions($optionRow === false ? null : $this->carMapper->optionsToEntity($optionRow));
    }
}
