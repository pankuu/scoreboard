# Live Football World Cup Score Board

## Initialization project

#### In project root run:
1. prepare project based on Symfony framework
    * composer install
    * ./bin/console doctrine:database:create
    * ./bin/console doctrine:migrations:migrate
    * copy .env.example to .env
2. run project
    * php -S localhost:8000 -t public/ or symfony server:start

To check the quality of the code:

* vendor/bin/phpstan analyse --level 7 src
* PHP_CS_FIXER_IGNORE_ENV=1 vendor/bin/php-cs-fixer fix src
