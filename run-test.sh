
composer dump-env test
symfony console doctrine:database:drop --env=test --force --verbose
symfony console doctrine:database:create --env=test --verbose
symfony console doctrine:schema:update --env=test --force
bin/phpunit