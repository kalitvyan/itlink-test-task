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

test:
	docker compose exec php vendor/bin/codecept run

stan:
	docker compose exec php vendor/bin/phpstan analyse

lint:
	docker compose exec php vendor/bin/phpcs

fix:
	docker compose exec php vendor/bin/phpcbf

validation-key:
	php -r "echo bin2hex(random_bytes(16)), PHP_EOL;"
