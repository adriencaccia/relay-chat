.PHONY: help
.DEFAULT_GOAL := help
help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

.ONESHELL:
start: ## start db, pma, backend, relay
	docker-compose up -d
	symfony server:start -d
	cd client
	yarn relay --watch
