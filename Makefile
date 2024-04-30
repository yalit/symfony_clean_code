PHONY: tests

# Inspired from https://www.strangebuzz.com/en/snippets/the-perfect-makefile-for-symfony
USER_ID:=$(shell id -u)
GROUP_ID:=$(shell id -g)

export USER_ID
export GROUP_ID

# Alias
PHP_CONTAINER = hexa-php

DOCKER = docker
SYMFONY =  ${DOCKER} exec -it ${PHP_CONTAINER} symfony
CONSOLE = ${DOCKER} exec -it ${PHP_CONTAINER} bin/console
COMPOSER = ${DOCKER} exec -it ${PHP_CONTAINER} composer

VERSION := $(shell $$SHELL -c 'echo $$ZSH_VERSION')
read_param = $(if $(2), $(shell echo $(2)), $(if $(strip $VERSION), $(shell $$SHELL -c 'read param\?"$(1) : "; echo $$param'), $(shell $$SHELL -c 'read "$(1) : " param; echo $$param')))

help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

## —— Docker ————————————————————————————————————————————————————————————
build: down ## Docker container up (including build)
	${DOCKER} compose up -d --build

up: down ## Docker container up (without build)
	${DOCKER} compose up -d

down: ## Docker container down
	${DOCKER} compose down

## —— Composer ————————————————————————————————————————————————————————————
install: ## Install the project dependencies
	${COMPOSER} install

update: ## Update the project dependencies
	${COMPOSER} update

require: ## Require a new package
	${COMPOSER} require $(filter-out $@,$(MAKECMDGOALS))

require-dev: ## Require a new package
	${COMPOSER} require --dev $(filter-out $@,$(MAKECMDGOALS))


## —— Doctrine ————————————————————————————————————————————————————————————
db-create: db-drop ## Create the database
	${CONSOLE} doctrine:database:create --env=dev

db-drop: ## Drop the database
	${CONSOLE} doctrine:database:drop --force --env=dev

db-update: ## Update the database
	${CONSOLE} doctrine:schema:update --force --env=dev

db-fixtures: db-create db-update ## Load fixtures into the database
	${CONSOLE} doctrine:fixtures:load --no-interaction --env=dev

## —— Testing ————————————————————————————————————————————————————————————
tests-prepare: ## Prepare the test environment (database / fixtures)
	${CONSOLE} doctrine:database:drop --force --env=test
	${CONSOLE} doctrine:database:create --env=test
	${CONSOLE} doctrine:schema:update --force --env=test
	${CONSOLE} doctrine:fixtures:load --no-interaction --env=test

## —— Symfony ————————————————————————————————————————————————————————————
server: ## Start the Symfony server
	${SYMFONY} serve -d
