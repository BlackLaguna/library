init-test-db:
	php bin/console --env=test doctrine:database:drop --force --if-exists
	php bin/console --env=test doctrine:database:create
	php bin/console --env=test doctrine:migration:migrate --no-interaction

test:
	php bin/console --env=test c:c
	php bin/phpunit

run:
#	composer install --no-interaction
#	php bin/console --env=dev doctrine:database:create --if-not-exists
#	php bin/console --env=dev doctrine:migration:migrate --no-interaction

up:
	docker compose up -d

analyze:
	php vendor/bin/phpstan analyse src --level 9
	php vendor/bin/ecs