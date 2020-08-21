ARGS = $(filter-out $@,$(MAKECMDGOALS))
MAKEFLAGS += --silent
.PHONY : list start up down login

DOCKER_UID=1000
DOCKER_GID=1000
DOCKER_CONTAINER=web
PROD_FILE=docker-compose.prod.yml
CINSTALL=bash -c 'composer install --no-dev --ignore-platform-reqs'

define call_docker
	APPLICATION_UID=$(id -u) APPLICATION_GID=$(id -g) docker-compose $(1) $(2)
endef

define call_docker_prod
	APPLICATION_UID=$(id -u) APPLICATION_GID=$(id -g) docker-compose -f $(PROD_FILE) $(1) $(2)
endef

list:
	bash -c "echo; $(MAKE) -p no_targets__ | awk -F':' '/^[a-zA-Z0-9][^\$$#\/\\t=]*:([^=]|$$)/ {split(\$$1,A,/ /);for(i in A)print A[i]}' | grep -v '__\$$' | grep -v 'Makefile'| sort"

start:
	$(call call_docker, pull)
	$(call call_docker, build)
	make up
	make login

up:
	$(call call_docker, up -d, $(ARGS))

down:
	$(call call_docker, down, $(ARGS))

login:
	docker-compose exec -u $(DOCKER_UID):$(DOCKER_GID) $(DOCKER_CONTAINER) bash

deploy:
	$(call call_docker_prod, pull)
	$(call call_docker_prod, up -d)
	docker-compose -f $(PROD_FILE) exec -u $(DOCKER_UID):$(DOCKER_GID) $(DOCKER_CONTAINER) $(CINSTALL)

do:
	$(call call_docker, $(ARGS))

%:
	@:
