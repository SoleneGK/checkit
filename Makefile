#!make
##############################################################################################
# Server-ControlScript
#---------------------------------------------------------------------------------------------
# This is the control script for ServerCore v1.0
#---------------------------------------------------------------------------------------------
##############################################################################################

include .env
export $(shell sed 's/=.*//' .env)

##########################################################
# Server Setup
##########################################################
setup-server:
	touch .env
	cp ./saved-config/$(SERVER_NAME)/.env-server .env
	mkdir -p $(PWD)/data/server-core/traefik-data/
	cp ./saved-config/$(SERVER_NAME)/traefik-sample.toml $(PWD)/data/server-core/traefik-data/traefik.toml
	mkdir -p $(PWD)/data/checkit/conf
	cp -r ./saved-config/$(SERVER_NAME)/conf/* $(PWD)/data/checkit/conf/


	
##########################################################
# Networks control 
##########################################################
##########################################################
#### proxy
##########################################################
create-proxy-network:
	docker network ls|grep $(PROXY_NETWORK_NAME) > /dev/null || docker network create --driver bridge --subnet=192.$(SERVER_INT).$(PROXY_NETWORK_INT).0/24 $(PROXY_NETWORK_NAME)
delete-proxy-network:
	docker network rm $(PROXY_NETWORK_NAME)

##########################################################
#### app 
##########################################################
create-app-network:
	docker network ls|grep $(APP_NETWORK_NAME) > /dev/null || docker network create --driver bridge --subnet=192.$(SERVER_INT).$(APP_NETWORK_INT).0/24 $(APP_NETWORK_NAME)
delete-app-network:
	docker network rm $(APP_NETWORK_NAME)

##########################################################
# control script 
##########################################################
##########################################################
#### general
##########################################################
up:
	make build-networks
	make up-core
	make up-app

down:
	make down-core
	make down-app

restart:
	make down
	make up
	make set-permissions

build-networks:
	make create-proxy-network
	make create-app-network

delete-networks: 
#high failure risque due to external endpoint still connected.
# /!/ USE WITH CARE /!/
	make delete-proxy-network
	make delete-app-network
		
##########################################################
#### Server-core 
##########################################################
up-core:
	docker-compose -f server-core/docker-compose-core.yml -p $(SERVER_NAME) up -d
down-core:
	docker-compose -f server-core/docker-compose-core.yml -p $(SERVER_NAME) down

##########################################################
#### Application
##########################################################
up-app:
	docker-compose -f sources/docker-compose-website.yml -p $(PROJECT_NAME) up -d
down-app:
	docker-compose -f sources/docker-compose-website.yml -p $(PROJECT_NAME) down

set-permissions:
	docker exec checkit-app chown www-data /var/lib/
	docker exec checkit-app chmod 777 /var/lib/
	docker exec checkit-app chown www-data -R /app/var
	docker exec checkit-app chmod 777 -R /app/var

install:
	make up
	docker exec checkit-app composer install
	docker exec checkit-app php bin/console doctrine:schema:create
	make restart

######################################
# pseudo ssh commands
######################################
bash-web:
	docker exec -ti checkit-web bash

bash-app:
	docker exec -ti checkit-app bash

bash-mysql:
	docker exec -ti checkit-mariadb bash

bash-phpmyadmin:
	docker exec -ti checkit-phpmyadmin bash
	
######################################
# showing last 50 lines of logs
######################################
make showlogs-web:
	docker logs --tail 50 -t checkit-web

make showlogs-app:
	docker logs --tail 50 -t checkit-app

make showlogs-mysql:
	docker logs --tail 50 -t checkit-mysql