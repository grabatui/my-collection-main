up: docker-up
init: docker-down-clear docker-pull docker-build docker-up project-init
test: project-test

project-init: project-composer-install

project-composer-install:
	docker-compose run --rm php-cli composer install

project-test:
	docker-compose run --rm php-cli php bin/phpunit

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-pull:
	docker-compose pull

docker-build:
	docker-compose build

phpstan:
	docker-compose run --rm php-cli ./vendor/bin/phpstan analyse src

cs-fixer:
	docker-compose run --rm php-cli ./vendor/bin/php-cs-fixer fix