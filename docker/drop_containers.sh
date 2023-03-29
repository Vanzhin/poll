#!/bin/bash
mkdir -p ./db/mysql
chown -R 1001:1001 ./db/mysql
if (which docker-compose  > /dev/null 2>&1); then
    docker-compose down
elif (which podman-compose  > /dev/null 2>&1); then
    podman-compose down
else
    echo "Can not find docker-compose or podman-compose"
    exit 1
fi
# docker stop $(docker ps -qa) && docker rm $(docker ps -qa) && docker rmi -f $(docker images -qa ) && docker volume rm $(docker volume ls -q) && docker network rm $(docker network ls -q)
