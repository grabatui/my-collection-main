# Variables
DOCKER_COMPOSE = docker-compose
EXEC_PHP = $(DOCKER_COMPOSE) exec php
COMPOSER = $(DOCKER_COMPOSE) exec php composer
SYMFONY = $(EXEC_PHP) php bin/console

init: down-clear pull build up project-init
restart: down up

project-init: p-composer-install p-npm-install p-npm-build

### Main
up:
	docker-compose up -d

down:
	docker-compose down --remove-orphans

test:
	${EXEC_PHP} php bin/phpunit

down-clear:
	docker-compose down -v --remove-orphans

pull:
	docker-compose pull

build:
	docker-compose build



# Helpers
p-cache-clear:
	${SYMFONY} cache:pool:clear --all
	${SYMFONY} cache:clear



### Composer
p-composer-install:
	docker-compose exec php composer install



### Front
p-npm-install:
	docker-compose run --rm encore npm install

p-npm-build:
	docker-compose run --rm encore npm run dev

p-npm-watch:
	docker-compose run --rm encore npm run watch



### Checkers
phpstan:
	docker-compose exec php ./vendor/bin/phpstan analyse src

cs-fixer:
	docker-compose exec php ./vendor/bin/php-cs-fixer fix



### Database
p-db-recreate: p-db-drop p-db-create p-migrations-migrate

p-migrations-diff:
	$(SYMFONY) doctrine:migrations:diff --no-debug

p-migrations-migrate:
	$(SYMFONY) doctrine:migrations:migrate --no-interaction --no-debug

p-db-drop:
	$(SYMFONY) doctrine:database:drop --if-exists --force

p-db-create:
	$(SYMFONY) doctrine:database:create --if-not-exists



### Test
p-migrations-migrate-test:
	$(SYMFONY) doctrine:migrations:migrate --no-interaction --no-debug --env=test

p-db-drop-test:
	$(SYMFONY) doctrine:database:drop --env=test --if-exists --force

p-db-create-test:
	$(SYMFONY) doctrine:database:create --env=test --if-not-exists
