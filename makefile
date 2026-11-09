.PHONY: run local test

local: 
	docker compose -f docker-compose.yml -f compose.local.yml up --watch

# Unit tests run in the php image with the working tree mounted, so dev
# dependencies land in the (git-ignored) host vendor/ and never in the image.
test:
	docker compose -f docker-compose.yml -f compose.local.yml run --rm --no-deps -v "$(CURDIR)/php/htdocs/webapp/www:/usr/share/nginx/html" php sh -c "composer install -q && vendor/bin/phpunit"
