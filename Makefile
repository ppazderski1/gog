VERSION?=dev-$(shell date +%s)

.PHONY: tests

composer-install:
	docker-compose run --rm php bin/composer.phar install;

composer-update:
	docker-compose run --rm php bin/composer.phar update;

start-dbs:
	docker-compose exec php ./bin/console doctrine:schema:update --force --env=test;
	docker-compose exec php ./bin/console doctrine:schema:update --force --env=dev;
	docker-compose exec php ./bin/console doctrine:fixtures:load --no-interaction --env=test;
	docker-compose exec php ./bin/console doctrine:fixtures:load --no-interaction --env=dev;
	docker-compose exec php ./bin/console doctrine:migrations:migrate --no-interaction --env=test;
	docker-compose exec php ./bin/console doctrine:migrations:migrate --no-interaction --env=dev;

start-tests:
	docker-compose exec php ./vendor/bin/phpunit --coverage-html /app/docs/test-coverage;