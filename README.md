# Audit package for tracking entity changes

## To install
1. `composer require homeapp/audit`
2. `bin/console doctrine:migrations:diff`
3. Отредактируйте миграцию и запустите. 
4. Создать сервис который имплементирует интервейс ActorInfoFetcherInterface и зарегестировать его.
```
 Homeapp\AuditBundle\ActorInfoFetcherInterface: '@App\Audit\ActorInfoFetcher'
``` 
PS: есть баг нужно добавить такой хак в service_test.yml
```
App\Audit\ActorInfoFetcher:
 autowire: true
 arguments:
   - '@test.service_container' #https://github.com/Codeception/module-symfony/issues/34
```
5. Сконфигурировать Auditable класc. Передав в аргумент classMap список entity которые нужно трекать
```
 Homeapp\AuditBundle\Auditable:
     arguments:
         $classMap:
             - App\Entity\UserRole
```
## TODO



1. Write instruction
1. Refactor migrations

# For development

## To fix code style issues

`vendor/bin/php-cs-fixer fix`

## To run test

`vendor/bin/phpunit`

## To run static analizer

`vendor/bin/psalm --no-cache`

# TODO

1. Remove minimum-stability: dev when [BackwardCompatibilityCheck](https://github.com/Roave/BackwardCompatibilityCheck)
   will be released 5.1 version
