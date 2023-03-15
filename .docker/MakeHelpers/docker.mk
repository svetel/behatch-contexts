build: ## Build containers
	$(DOCKER_COMPOSE) build

down: ## Stop containers
	$(DOCKER_COMPOSE) down --remove-orphans

up: ## Set up containers
	$(DOCKER_COMPOSE) up -d --remove-orphans

rebuild: ## Re-build containers
	$(DOCKER_COMPOSE) build --pull --force-rm --no-cache
