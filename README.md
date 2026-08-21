# itlink-test-task

Backend-сервис на Yii2 + PostgreSQL согласно тестового задания.
Полный текст задания, как он был получен, в [TASK.md](TASK.md).

## Архитектура

Слои, правило зависимостей, конвенции именования, единый формат ответа/ошибок и то, как
добавить новый модуль по этой же схеме, описаны в отдельном публичном
репозитории-бойлерплейте (создан в рамках тестового задания), на основе которого сделан сервис:
[ARCHITECTURE.md](https://github.com/kalitvyan/yii2-api-boilerplate/blob/master/ARCHITECTURE.md).

Коротко: 
- `Domain` (сущности/value objects/интерфейсы репозиториев, без зависимости от фреймворка) 
- `Application` (use case'ы) 
- `Infrastructure` (реализация репозиториев на Query Builder, без ActiveRecord) 
- `Interfaces\Http` (контроллеры) зависят от `Application`, а инфраструктура подключается через DI-контейнер в `config/web.php`.

## Стек

- PHP 8.4, [Yii2](https://www.yiiframework.com/) бизнес-логика вынесена в `src/` и от фреймворка не зависит
- PostgreSQL 17
- Docker Compose (php-fpm + nginx + postgres) для локальной разработки
- [Codeception](https://codeception.com/) для тестов, [PHPStan](https://phpstan.org/)
  (level 6) для статического анализа

## Быстрый старт

```bash
git clone https://github.com/kalitvyan/itlink-test-task.git
cd itlink-test-task
cp .env.example .env
```

Сгенерировать ключ валидации cookie:

```bash
make validation-key
```

Поднять стек и накатить миграции:

```bash
make build
make up
make migrate
```

API доступен на `http://127.0.0.1:8080`. Проверка, что всё работает:

```bash
curl http://127.0.0.1:8080/api/v1/health
# {"data":{"status":"ok"}}
```

## API

Единый формат ответа для всего API 
успех: 
```json 
{"data": ..., "meta"?: {...}}
```
ошибка:
```json
{"errors": [{"status", "code", "title", "detail", "source"?}]}
``` 
HTTP-статус всегда согласован с содержимым (`201` на создание, `404`/`422`/`500` на ошибки).

### `GET /api/v1/health`

Liveness-проба, без обращения к БД.

```bash
curl http://127.0.0.1:8080/api/v1/health
# {"data":{"status":"ok"}}
```

### `GET /api/v1/health/ready`

Readiness-проба, дополнительно проверяет соединение с БД.

```bash
curl http://127.0.0.1:8080/api/v1/health/ready
# {"data":{"status":"ready"}}
```

### `POST /api/v1/car/create`

Создаёт объявление. `options` необязателен: можно не передавать поле, передать `null`,
либо передать объект, тогда все 5 полей (`brand`, `model`, `year`, `body`, `mileage`)
обязательны.

Без технических характеристик:

```bash
curl -X POST http://127.0.0.1:8080/api/v1/car/create \
  -H 'Content-Type: application/json' \
  -d '{
        "title": "Audi A6",
        "description": "One owner, full service history",
        "price": 28000,
        "photo_url": "https://picsum.photos/seed/audi-a6/800/600",
        "contacts": "+1234567890"
      }'
```

```json
{
  "data": {
    "id": 1,
    "title": "Audi A6",
    "description": "One owner, full service history",
    "price": "28000",
    "photo_url": "https://picsum.photos/seed/audi-a6/800/600",
    "contacts": "+1234567890",
    "created_at": "2026-08-21T10:59:55+00:00",
    "options": null
  }
}
```
`HTTP 201 Created`

С техническими характеристиками:

```bash
curl -X POST http://127.0.0.1:8080/api/v1/car/create \
  -H 'Content-Type: application/json' \
  -d '{
        "title": "Range Rover",
        "description": "Well maintained, garage kept",
        "price": 52000.50,
        "photo_url": "https://picsum.photos/seed/range-rover/800/600",
        "contacts": "+1987654321",
        "options": {"brand": "Land Rover", "model": "Range Rover", "year": 2022, "body": "SUV", "mileage": 15000}
      }'
```

```json
{
  "data": {
    "id": 2,
    "title": "Range Rover",
    "description": "Well maintained, garage kept",
    "price": "52000.5",
    "photo_url": "https://picsum.photos/seed/range-rover/800/600",
    "contacts": "+1987654321",
    "created_at": "2026-08-21T11:01:19+00:00",
    "options": {
      "brand": "Land Rover",
      "model": "Range Rover",
      "year": 2022,
      "body": "SUV",
      "mileage": 15000
    }
  }
}
```
`HTTP 201 Created`

Ошибка валидации (например, `options` передан не полностью):

```bash
curl -X POST http://127.0.0.1:8080/api/v1/car/create \
  -H 'Content-Type: application/json' \
  -d '{
        "title": "Range Rover",
        "description": "d",
        "price": 52000.50,
        "photo_url": "https://picsum.photos/seed/range-rover/800/600",
        "contacts": "+1987654321",
        "options": {"brand": "Land Rover", "model": "Range Rover", "year": 2022, "body": "SUV"}
      }'
```

```json
{
  "errors": [
    {
      "status": "422",
      "code": "validation_failed",
      "title": "Unprocessable Entity",
      "detail": "Mileage cannot be blank.",
      "source": {
        "pointer": "/data/attributes/options/mileage"
      }
    }
  ]
}
```
`HTTP 422 Unprocessable Entity`, `source.pointer` указывает
конкретно на `options/mileage`, а не просто на факт "что-то не так".

### `GET /api/v1/car/{id}`

```bash
curl http://127.0.0.1:8080/api/v1/car/2
```

```json
{
  "data": {
    "id": 11,
    "title": "Range Rover",
    "description": "Well maintained, garage kept",
    "price": "52000.50",
    "photo_url": "https://picsum.photos/seed/range-rover/800/600",
    "contacts": "+1987654321",
    "created_at": "2026-08-21T11:01:19+00:00",
    "options": {
      "brand": "Land Rover",
      "model": "Range Rover",
      "year": 2022,
      "body": "SUV",
      "mileage": 15000
    }
  }
}
```
`HTTP 200 OK`

Несуществующий id:

```bash
curl http://127.0.0.1:8080/api/v1/car/999999
```

```json
{
  "errors": [
    {
      "status": "404",
      "code": "resource_not_found",
      "title": "Resource Not Found",
      "detail": "Car with id 999999 was not found."
    }
  ]
}
```
`HTTP 404 Not Found`

### `GET /api/v1/car/list?page=1`

20 объявлений на странице, новые первыми. `meta` содержит `page`, `pageSize`,
`totalCount`, `totalPages`.

```bash
curl "http://127.0.0.1:8080/api/v1/car/list?page=1"
```

```json
{
  "data": [
    {
      "id": 2,
      "title": "Range Rover",
      //...
    },
    {
      "id": 1,
      "title": "Audi A6",
      //...
    },    
  ],
  "meta": {
    "page": 1,
    "pageSize": 20,
    "totalCount": 2,
    "totalPages": 1
  }
}
```
`HTTP 200 OK`

## Тесты

- `tests/Unit`: `Domain`/`Application` классы без обращения к фреймворку/БД:
  `ValidationFailedExceptionTest`, `EntityNotFoundExceptionTest` (маппинг исключений в
  JSON:API-ошибку), `CreateCarServiceTest` unit-тест на создание объявления (репозиторий замокан).

- `tests/Functional`: `HealthCest` и `CarCest` (создание с опциями и без, ошибка
  валидации, 404, пагинация листинга) гоняют приложение in-process через модуль Yii2 и
  проверяют реальные HTTP-статусы и JSON-конверт.

Функциональные тесты используют отдельную БД (`TEST_DB_NAME` в `.env`, по умолчанию
`<DB_NAME>_test`) её нужно один раз замигрировать:

```bash
make migrate-test
make test
# либо без Docker:
vendor/bin/codecept run
```

Статический анализ и код-стайл:

```bash
make stan   # PHPStan, level 6
make lint   # PHPCS (yii2-coding-standards)
```
