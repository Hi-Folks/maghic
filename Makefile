.PHONY : help phpstan test phpcs
.DEFAULT_GOAL:=help

help:           ## Show this help.
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//'

phpstan: ## Execute phpstan
	vendor/bin/phpstan analyse -c ./phpstan.neon --no-progress

test: ## Execute tests
	vendor/bin/pest

testcoverage: ## Execute tests with coverage report
	vendor/bin/pest --coverage

phpcs: ## execute phpcs
	vendor/bin/phpcs

phpfix: ## Fix some warnings from phpcs
	vendor/bin/phpcbf

allcheck: phpcs phpstan test ## it performs all check (phpcs, phpstan, tests)
