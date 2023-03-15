DOCKER_COMPOSE_DIR?=./.docker
DOCKER_COMPOSE_FILE?=$(DOCKER_COMPOSE_DIR)/docker-compose.yml
DOCKER_COMPOSE=docker-compose -f $(DOCKER_COMPOSE_FILE) --project-directory $(DOCKER_COMPOSE_DIR)
