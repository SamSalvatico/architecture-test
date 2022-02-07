# If MacOs, uncomment next line
#.RECIPEPREFIX +=
.DEFAULT_GOAL := help

COMPOSER :=$(shell which composer | grep -o composer)
ENV_EXISTS:=$(shell ls -la | grep '.env$$')
## 2> no error if file does not exist
APP_KEY :=$(shell cat .env 2>/dev/null | grep '^APP_KEY=$$')

.PHONY: help
help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: install
install: composer sail-up key-gen check-env clear-all ## Main task

.PHONY: composer
composer:
ifeq ($(COMPOSER),composer)
	@composer install --ignore-platform-reqs --no-scripts
else
	@docker run --rm --interactive --tty \
		--volume $(PWD):/app \
		--user $(id -u):$(id -g) \
		composer:2.0 install --ignore-platform-reqs --no-scripts
endif

.PHONY: sail-up
sail-up:
	@./vendor/bin/sail up --build --remove-orphans -d

.PHONY: key-gen
key-gen: 
ifeq ($(ENV_EXISTS),)
	@cp .env.example .env
else
	@echo '.env file is already present'
endif
ifeq ($(APP_KEY),APP_KEY=)
	@./vendor/bin/sail artisan key:generate --force
else ifeq ($(APP_KEY),)
	@./vendor/bin/sail artisan key:generate --force
else
	@echo 'APP_KEY already present'
endif

.PHONY: npm-install
npm-install:
	@./vendor/bin/sail npm install
	@./vendor/bin/sail npm run dev

.PHONY: test
test: sail-up php-cs phpstan phpunit ## Test task: php-cs, phpunit

.PHONY: phpunit
phpunit: ## PHPUnit
	@./vendor/bin/sail php vendor/bin/phpunit

.PHONY: phpunit-coverage
phpunit-coverage: sail-up-debug
	@./vendor/bin/sail php vendor/bin/phpunit --coverage-html coverage

.PHONY: php-cs
php-cs: ## PHP Code Sniffer
	@./vendor/bin/sail php vendor/bin/phpcs

.PHONY: cache-all
cache-all:
ifeq ($(COMPOSER),composer)
	@composer cache-all
else
	@docker run --rm --interactive --tty \
		--volume $(PWD):/app \
		--user $(id -u):$(id -g) \
		composer:2.0 cache-all
endif

.PHONY: phpstan
phpstan: ## PHPStan
	@./vendor/bin/sail php vendor/bin/phpstan

.PHONY: clear-all
clear-all:
ifeq ($(COMPOSER),composer)
	@composer clear-all
else
	@docker run --rm --interactive --tty \
		--volume $(PWD):/app \
		--user $(id -u):$(id -g) \
		composer:2.0 clear-all
endif

.PHONY: check-env
check-env:
	@./vendor/bin/sail php bin/check-env.php