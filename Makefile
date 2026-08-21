up:
	docker compose up -d

down:
	docker compose down

build:
	docker compose build

build-no-cache:
	docker compose build --no-cache

restart:
	docker compose restart

logs:
	docker compose logs -f

shell:
	docker compose exec php bash

yii:
	docker compose exec php php yii

migrate:
	docker compose exec php php yii migrate

migrate-test:
	docker compose exec -e DB_NAME=$${TEST_DB_NAME:-app_test} php php yii migrate

migrate-down:
	docker compose exec php php yii migrate/down $(n)

migrate-create:
	docker compose exec php php yii migrate/create $(name)

test:
	docker compose exec php vendor/bin/codecept run

stan:
	docker compose exec php vendor/bin/phpstan analyse

lint:
	docker compose exec php vendor/bin/phpcs

fix:
	docker compose exec php vendor/bin/phpcbf

validation-key:
	@test -f .env || { echo ".env not found — run: cp .env.example .env"; exit 1; }
	@key=$$(php -r "echo bin2hex(random_bytes(16));"); \
	sed -i.bak "s/^COOKIE_VALIDATION_KEY=.*/COOKIE_VALIDATION_KEY=$$key/" .env && rm -f .env.bak; \
	echo "COOKIE_VALIDATION_KEY updated in .env"
