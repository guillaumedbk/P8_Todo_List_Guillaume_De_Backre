# ToDo & Co


Améliorez une application existante de To Do, projet 8 de la formation développeurs d'application PHP/Symfony Openclassrooms.

## Clone

```bash
git clone https://github.com/guillaumedbk/P8_Todo_List_Guillaume_De_Backre
```

## Install

```bash
composer install
```
## DB Configuration
- Modifiy .env with your db connection informations

```bash
# Create DB
php bin/console doctrine:database:create

# Create db structure
php bin/console doctrine:migrations:migrate


# Load fixtures
php bin/console hautelook:fixtures:load
```
## Launch server

```bash
symfony server:start
```
## Launch tests

```bash
./vendor/bin/phpunit --coverage-text
```

## Contributing


## Code Quality
https://insight.symfony.com/projects/512f9597-fecd-4889-a98d-d30539c32bed

ToDoList
========

Base du projet #8 : Améliorez un projet existant

https://openclassrooms.com/projects/ameliorer-un-projet-existant-1
