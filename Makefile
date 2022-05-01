APP_CONTAINER = logs-processor_php_1
DB_CONTAINER = logs-processor_database_1


.DEFAULT_GOAL = help

build: ## Builds the Docker images
    POSTGRES_USER=legal1 POSTGRES_PASSWORD=legal1 POSTGRES_DATABASE=legal_one SYMFONY_VERSION=5.4.* HTTP_PORT=8000 SERVER_NAME="http://logs-processor.localhost" docker-compose build --pull --no-cache

up: ## Start the Docker containers in detached mode
	HTTP_PORT=8000 SERVER_NAME="http://logs-processor.localhost" docker-compose up -d

stop: ## Stop containers
	docker-compose stop

down: ## Stop and remove containers
	docker-compose down --remove-orphans

shell: ## Access the main app container
	docker exec -it $(APP_CONTAINER) /bin/sh

shell-db: ## Access the DB container
	docker exec -it $(DB_CONTAINER) /bin/sh

help: ## Display the Makefile help
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
