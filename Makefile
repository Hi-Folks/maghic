.PHONY : help phpstan test phpcs
.DEFAULT_GOAL:=help

help:           ## Show this help.
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//'

phpstan: ## Execute phpstan
	vendor/bin/phpstan analyse -c ./phpstan.neon --no-progress

test: ## Execute phpunit
	vendor/bin/pest

phpcs: ## execute phpcs
	vendor/bin/phpcs --standard=PSR12 app

phpfix: ## Fix some warnings from phpcs
	vendor/bin/phpcbf --standard=PSR12  app

allcheck: phpcs phpstan test ## it performs all check (phpcs, phpstan, tests)
