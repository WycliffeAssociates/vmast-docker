.PHONY: run

local: 
	docker compose -f docker-compose.yml -f compose.local.yml up --watch
build: 
	export VMAST_IMAGE_TAG="local" \
  && cd ./web \
	&& docker build -f ./web/Dockerfile -t bibletranslationtools/vmast-web:$${VMAST_IMAGE_TAG} . \
	&& cd ../php \
	&& docker build -t bibletranslationtools/vmast-php:$${VMAST_IMAGE_TAG} . \
	&& cd ../node \
	&& docker build -t bibletranslationtools/vmast-node:$${VMAST_IMAGE_TAG} . \

