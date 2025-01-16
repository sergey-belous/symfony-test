main:
	docker-compose up -d --build
	docker exec symfony-php-apache bash -c 'cd /app && COMPOSER_MEMORY_LIMIT=-1 composer install  --no-interaction'
	make reset-database
	make reset-test-database

reset-database:
	docker exec -t symfony-php-apache bash -c '/app/bin/console doctrine:database:create --if-not-exists --no-interaction'
	docker exec -t symfony-php-apache bash -c '/app/bin/console doctrine:schema:drop --force --full-database'
	docker exec -t symfony-php-apache bash -c '/app/bin/console doctrine:migrations:migrate --no-interaction'
	docker exec -t symfony-php-apache bash -c '/app/bin/console doctrine:fixtures:load --no-interaction'

reset-test-database:
	docker exec -t symfony-php-apache bash -c '/app/bin/console --env=test doctrine:database:create --if-not-exists --no-interaction'
	docker exec -t symfony-php-apache bash -c '/app/bin/console --env=test doctrine:schema:drop --force --full-database'
	docker exec -t symfony-php-apache bash -c '/app/bin/console --env=test doctrine:migrations:migrate --no-interaction'
	docker exec -t symfony-php-apache bash -c '/app/bin/console --env=test doctrine:fixtures:load --no-interaction'

run-tests:
	docker exec -it symfony-php-apache bash -c 'cd app && ./bin/phpunit --testdox'