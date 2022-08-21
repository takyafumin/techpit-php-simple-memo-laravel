#!/usr/bin/env bash

CMD_DOCKER='docker-compose -f .docker_memo_laravel/docker-compose.yml'

if [ $# -eq 0 ]; then
    ${CMD_DOCKER} ps

elif [ "$1" == "ps" ]; then
    ${CMD_DOCKER} ps

elif [ "$1" == "up" ]; then
    ${CMD_DOCKER} up -d

elif [ "$1" == "down" ]; then
    shift 1;
    ${CMD_DOCKER} down $@

elif [ "$1" == "destroy" ]; then
    ${CMD_DOCKER} down -v --remove-orphense

elif [ "$1" == "bash" ]; then
    ${CMD_DOCKER} exec php /bin/bash

elif [ "$1" == "clear" ]; then
    ${CMD_DOCKER} exec php php artisan cache:clear
    ${CMD_DOCKER} exec php php artisan view:clear

elif [ "$1" == "npm-ci" ]; then
    ${CMD_DOCKER} exec php npm ci

elif [ "$1" == "dev" ]; then
    ${CMD_DOCKER} exec php npm run dev

elif [ "$1" == "ci" ]; then
    cd .docker_memo_laravel/
    ${CMD_DOCKER} exec php ./vendor/bin/phpcbf
    ${CMD_DOCKER} exec php ./vendor/bin/phpcs
    ${CMD_DOCKER} exec php ./vendor/bin/phpmd app text ruleset.xml

else
    # transfer to 'docker-compose' command
    ${CMD_DOCKER} "$@"
fi
