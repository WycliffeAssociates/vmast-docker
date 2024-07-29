.PHONY: run

local: 
	docker compose -f docker-compose.yml -f compose.local.yml up --watch
