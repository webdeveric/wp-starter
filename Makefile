SERVICE_ID := wp-starter
ORG := webdeveric
IMAGE_NAME := $(ORG)/$(SERVICE_ID)
HOST_PORT := 8000
CONTAINER_PORT := 80
SHELL := /usr/bin/env bash

.PHONY: instructions install build dev test
.PHONY: clean startover rm-containers rm-images fix-owner fix-perms folders

instructions:
	-@ echo -e "\n\tRun these commands to get started:\n"
	-@ echo -e "\tmake install"
	-@ echo -e "\tmake dev\n"

dev: folders rm-containers
	docker-compose up dev

test:
	docker-compose run --rm --user="$(shell id -u):$(shell id -g)" cli vendor/bin/phpunit ./tests/

build: folders install
	@echo "Building Docker image"
	@DOCKER_BUILDKIT=1 docker image build -t $(IMAGE_NAME) .
	@echo "$(IMAGE_NAME) has been created"

install:
	@echo "Installing dependencies"
	@docker-compose run --rm --user="$(shell id -u):$(shell id -g)" composer install

startover: clean fix-owner
	sudo rm -Rf ./vendor/ ./public/index.php ./public/cms/ ./public/wp-content/

clean: rm-containers rm-images

rm-containers:
	@echo "Removing containers..."
	-@ docker-compose down --remove-orphans
	@echo "Killing running containers"
	-@ docker container ls -aq -f "status=running" -f "label=com.webdeveric.wp-starter.service-id=$(SERVICE_ID)" | xargs -I {} docker container kill {}
	@echo "Removing exited containers"
	-@ docker container ls -aq -f "status=exited" -f "label=com.webdeveric.wp-starter.service-id=$(SERVICE_ID)" | xargs -I {} docker container rm {}
	@echo "Containers have been removed"

rm-images:
	@echo "Removing images"
	@docker image ls -q -f "label=com.webdeveric.wp-starter.service-id=$(SERVICE_ID)" | xargs -I {} docker image rm -f {}
	@echo "Images removed"

folders:
	@echo "Creating project folders"
	-@ mkdir -p ./{packages,public,tests,vendor}
	@echo "Creating WordPress folders"
	-@ mkdir -p ./public/wp-content/{uploads,plugins,mu-plugins,themes}
	-@ mkdir -p ./public/wp-content/uploads/wp-personal-data-exports

fix-owner:
	find . -maxdepth 1 -type d \( -name public -o -name packages -o -name xprofiler -o -name vendor \) -exec sudo chown -R $(shell id -u):$(shell id -g) {} \;

fix-perms: folders
	sudo find ./{packages,public,vendor} -type d -not -perm 775 -exec sudo chmod 775 {} \;
	sudo find ./{packages,public,vendor} -type f -not -perm 664 -exec sudo chmod 664 {} \;

dump-db:
	-@ echo "Dumping DB..."
	-@ docker-compose exec db mysqldump -u wp -pwp wp --skip-comments -r /docker-entrypoint-initdb.d/wp-starter.sql > /dev/null
	-@ echo "Done!"
