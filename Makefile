export PROJECT_NAME := translation-hub-challenge
export CURRENT_PATH := $(shell pwd)
export TERRAFORM_CONTAINER := terraform
export PHP_CONTAINER := php
export VUE_CONTAINER := vue

PHP_CS_FIXER_ARGS = --show-progress=dots --rules=@PSR12
DOCKER_COMPOSE=docker-compose -p ${PROJECT_NAME} -f ${CURRENT_PATH}/ops/docker/docker-compose.yml -f ${CURRENT_PATH}/ops/docker/docker-compose.dev.yml

start: docker-build docker-up logs

restart: start stop
dev: docker-build docker-up composer-install database-create execute-migrations logs

stop:
	@${DOCKER_COMPOSE} down --remove-orphans

docker-build:
	@${DOCKER_COMPOSE} build

docker-up:
	@${DOCKER_COMPOSE} up -d

logs:
	@${DOCKER_COMPOSE} logs -f

#Database
database-create:
	@${DOCKER_COMPOSE} exec ${PHP_CONTAINER} php bin/console doctrine:database:create --if-not-exists

generate-migrations:
	@${DOCKER_COMPOSE} exec ${PHP_CONTAINER} php bin/console doctrine:migrations:diff

execute-migrations:
	@${DOCKER_COMPOSE} exec ${PHP_CONTAINER} php bin/console --no-interaction doctrine:migrations:migrate

#messenger
messenger-consume:
	@${DOCKER_COMPOSE} exec ${PHP_CONTAINER} php bin/console messenger:consume async -vv

messenger-debug:
	@${DOCKER_COMPOSE} exec ${PHP_CONTAINER} php bin/console debug:messenger

#cache
cache-clear:
	@${DOCKER_COMPOSE} exec -u www-data:www-data ${PHP_CONTAINER} php bin/console cache:clear

#composer
composer-install:
	cd code/app && composer install

composer-update:
	cd code/app && composer update

composer-require:
	cd code/app && composer require ${PACKAGE}

composer-remove:
	cd code/app && composer remove ${PACKAGE}

#tests and linting

lints-php:
	@${DOCKER_COMPOSE} exec ${PHP_CONTAINER} php vendor/bin/php-cs-fixer fix --config=.php_cs.dist --dry-run --diff --verbose $(PHP_CS_FIXER_ARGS)

lints-php-fix:
	@${DOCKER_COMPOSE} exec ${PHP_CONTAINER} php vendor/bin/php-cs-fixer fix --verbose $(PHP_CS_FIXER_ARGS)

tests-php:
	@${DOCKER_COMPOSE} exec ${PHP_CONTAINER} bin/phpunit

tests-php-local:
	cd code/app && \
	bin/phpunit

#yarn

yarn-add:
	cd code/frontend && yarn add ${PACKAGE}

yarn-install:
	cd code/frontend && yarn install

yarn-serve:
	cd code/frontend && yarn serve

# ui

open-ui:
	xdg-open http://localhost

open-rabbitmq-manager:
	xdg-open http://localhost:15672

open-api:
	xdg-open http://localhost:8080/api

# translate command
translate:
	@${DOCKER_COMPOSE} exec ${PHP_CONTAINER} php bin/console app:translate ${PARAMS}
