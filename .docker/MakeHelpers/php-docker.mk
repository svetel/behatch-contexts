PHP_CONTAINER?=$$PHP_CONTAINER_NAME

run_php_server: ## Run e2e tests
	$(DOCKER_COMPOSE) exec -iT php-fpm php -S localhost:8080 -t tests/fixtures/www &> /dev/null &

e2e: ## Run e2e tests
	$(DOCKER_COMPOSE) exec -e ENV=test php-fpm php -dmemory_limit=-1 bin/behat --config behat.yml --stop-on-failure ${args} ${target_args}

test: ## Run all tests
	#$(MAKE) PHP_CONTAINER=$(PHP_CONTAINER) run_php_server
	$(eval target_args=--format=progress --tags '~@user&&~@skip&&~@javascript')
	$(MAKE) PHP_CONTAINER=$(PHP_CONTAINER) e2e

composer: ## Composer run command
	docker exec $(PHP_CONTAINER) composer ${args}

myphp: ## Enter to PHP node
	docker exec -it $(PHP_CONTAINER) bash

psalm: ## Run psalm
	$(DOCKER_COMPOSE) exec -e ENV=test php-fpm php vendor/bin/psalm -c psalm.xml
