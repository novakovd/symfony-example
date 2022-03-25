# Symfony + NGINX + MariaDB + Docker 

## How to run app
```shell
# In project root directory

docker-compose up -d
docker exec -it symfony-example-php-1 composer install
docker exec -it symfony-example-php-1 php bin/console doctrine:migrations:migrate

# Now app is available at http://localhost:8010
```
## How to run tests
```shell
docker exec -it symfony-example-php-1 php bin/console doctrine:fixtures:load
docker exec -it symfony-example-php-1 php bin/phpunit
```

## List endpoints
```shell
docker exec -it symfony-example-php-1 php bin/console debug:router | grep admin_api_ducks
```