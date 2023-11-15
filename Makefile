# Alias
DOCKER = docker
SYMFONY =  symfony
CONSOLE = ${SYMFONY} console

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

## —— Doctrine ————————————————————————————————————————————————————————————
db-create: ## Create the database
	${CONSOLE} doctrine:database:create --env=dev

db-drop: ## Drop the database
	${CONSOLE} doctrine:database:drop --force --env=dev

db-update: ## Update the database
	${CONSOLE} doctrine:schema:update --force --env=dev
