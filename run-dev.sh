
symfony server:stop
composer dump-env test
symfony console doctrine:database:drop --env=dev --force
symfony console doctrine:database:create --env=dev
symfony console doctrine:migrations:migrate --env=dev --no-interaction
symfony console doctrine:fixtures:load --env=dev --no-interaction
symfony server:start -d