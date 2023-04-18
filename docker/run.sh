#!/bin/bash
mkdir -p ./db/mysql ./nginx/logs
chown -R 1001:1001 ./db/mysql ./nginx/logs ../symfony

if (which docker-compose  > /dev/null 2>&1); then
    docker-compose up -d --build
elif (which podman-compose  > /dev/null 2>&1); then
    podman-compose up -d --build
else
    echo "Can not find docker-compose or podman-compose"
    exit 1
fi
