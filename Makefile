.PHONY: install help test dev stop-dev build bash test
.DEFAULT_GOAL   = help

include .env

PRIMARY_COLOR   		= \033[0;34m
PRIMARY_COLOR_BOLD   	= \033[1;34m

SUCCESS_COLOR   		= \033[0;32m
SUCCESS_COLOR_BOLD   	= \033[1;32m

DANGER_COLOR    		= \033[0;31m
DANGER_COLOR_BOLD    	= \033[1;31m

WARNING_COLOR   		= \033[0;33m
WARNING_COLOR_BOLD   	= \033[1;33m

NO_COLOR      			= \033[m

# For test
mariadb_test = docker-compose -f docker-compose-test.yaml exec mariadb mysql -psecret -e
php_test = docker-compose -f docker-compose-test.yaml exec php php

# For dev
php = docker-compose -f docker-compose-dev.yaml run --rm php php
bash = docker-compose -f docker-compose-dev.yaml run --rm php bash
composer = docker-compose -f docker-compose-dev.yaml run --rm php composer
npm = docker-compose -f docker-compose-dev.yaml run --rm asset_dev_server npm


node_modules: package.json
	@$(npm) install

vendor: composer.json
	@$(composer) install

install: vendor node_modules ## Install the composer dependencies and npm dependencies
	@echo "$(PRIMARY_COLOR)Migrating database...$(NO_COLOR)"
	@$(php) bin/console doctrine:database:drop --force --if-exists
	@$(php) bin/console doctrine:database:create
	@$(php) bin/console doctrine:migrations:migrate --no-interaction --quiet
	@$(php) bin/console doctrine:fixtures:load --no-interaction --no-debug

help: ## Display this help
	@awk 'BEGIN {FS = ":.*##"; } /^[a-zA-Z_-]+:.*?##/ { printf "$(PRIMARY_COLOR_BOLD)%-15s$(NO_COLOR) %s\n", $$1, $$2 }' $(MAKEFILE_LIST) | sort

dev: ## Run development servers
	@docker-compose -f docker-compose-dev.yaml up -d
	@echo "Dev server launched on http://localhost:$(APP_PORT)"
	@echo "Asset dev server launched on http://localhost:3000"

stop-dev: ## Stop development servers
	@docker-compose -f docker-compose-dev.yaml down
	@echo "Dev server stopped: http://localhost:$(APP_PORT)"
	@echo "Asset dev server stopped: http://localhost:3000"

build: install ## Build assets projects for production
	@rm -rf ./public/build/*
	@$(npm) run build

bash: ## Run bash in PHP container
	@$(bash)

test: ## Run tests
	@docker-compose -f docker-compose-test.yaml up -d
	@$(mariadb_test) "DROP DATABASE IF EXISTS $(APP_NAME)_test; CREATE DATABASE $(APP_NAME)_test"
	@$(php_test) bin/console doctrine:migrations:migrate --no-interaction --quiet --env=test -vvv
	@$(php_test) bin/console doctrine:fixtures:load --no-interaction --quiet --env=test
	@$(php_test) bin/phpunit --testdox
	@docker-compose -f docker-compose-test.yaml down
