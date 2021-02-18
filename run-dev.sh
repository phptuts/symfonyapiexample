
composer dump-env test
docker-compose up -d
## this is to let docker boot up
sleep 2
symfony console doctrine:database:drop --env=dev --force
symfony console doctrine:database:create --env=dev
symfony console doctrine:migrations:migrate --env=dev --no-interaction
symfony console doctrine:fixtures:load --env=dev --no-interaction
symfony server:start -d