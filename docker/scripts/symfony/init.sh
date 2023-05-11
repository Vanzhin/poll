#!/bin/bash

# shellcheck disable=SC1091

set -o errexit
set -o nounset
set -o pipefail
# set -o xtrace # Uncomment this line for debugging purposes

# Load libraries
. /opt/bitnami/scripts/liblog.sh


cd /app
if [ ! -f '/app/.firstrun_php' ]; then
    export COMPOSER_ALLOW_SUPERUSER=1
    touch '/app/.firstrun_php'
    info "Running composer install"
    composer --no-interaction install
    composer --no-interaction require doctrine/doctrine-fixtures-bundle --dev
    info "Running migrations"
    php bin/console --no-interaction doctrine:migrations:migrate
    info "Running migrations"
    php bin/console --no-interaction doctrine:fixtures:load --group=prod
    php bin/console --no-interaction lexik:jwt:generate-keypair --overwrite
fi
