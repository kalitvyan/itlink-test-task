<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Postgres;

use yii\db\Connection;
use yii\db\Query;

abstract class PostgresRepository
{
    public function __construct(
        protected readonly Connection $db,
        protected readonly DataMapperInterface $mapper,
    ) {
    }

    abstract protected function tableName(): string;

    protected function query(): Query
    {
        return (new Query())->from($this->tableName());
    }

    /**
     * @param array<string, mixed> $condition
     */
    protected function findOneRow(array $condition): ?object
    {
        $row = $this->query()->where($condition)->one($this->db);

        return $row === false ? null : $this->mapper->toEntity($row);
    }

    /**
     * @param array<string, mixed> $condition
     * @return object[]
     */
    protected function findRows(array $condition = []): array
    {
        $query = $this->query();

        if ($condition !== []) {
            $query->where($condition);
        }

        return array_map($this->mapper->toEntity(...), $query->all($this->db));
    }

    protected function insertRow(object $entity): void
    {
        $this->db->createCommand()
            ->insert($this->tableName(), $this->mapper->toRow($entity))
            ->execute();
    }

    /**
     * @param array<string, mixed> $condition
     */
    protected function updateRow(array $condition, object $entity): void
    {
        $this->db->createCommand()
            ->update($this->tableName(), $this->mapper->toRow($entity), $condition)
            ->execute();
    }

    /**
     * @param array<string, mixed> $condition
     */
    protected function deleteRow(array $condition): void
    {
        $this->db->createCommand()
            ->delete($this->tableName(), $condition)
            ->execute();
    }
}
