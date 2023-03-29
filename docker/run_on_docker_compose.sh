#!/bin/bash
mkdir -p ./db/mysql ./nginx/logs
chown -R 1001:1001 ./db/mysql ./nginx/logs

if (which docker-compose  > /dev/null 2>&1); then
    docker-compose stop && docker-compose rm -vf && docker-compose up -d
elif (which podman-compose  > /dev/null 2>&1); then
    podman-compose down && podman-compose up -d
else
    echo "Can not find docker-compose or podman-compose"
    exit 1
fi

