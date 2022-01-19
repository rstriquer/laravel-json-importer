# Laravel JSON Importer

System to illustrate the construction of an artisan command that will import a json file containing customer and credit card information in a single file.

## Demonstrated features:
- It handles giant files, regardless of size because it makes use of php iterators;
- Makes use of the transformer pattern to translate the information from the json file to the format used in the database;
- Illustrates use of HasOne relations from Laravel/Eloquent ORM;
- Implements feature test of created artisan command;
- Makes use of events and listeners to detach the import command from its execution by using non-synchronous queues;
- Uses a dat file to save the last imported record;

## Class-diagram

Below is an approximate example of the architecture used in a class diagram illustration


<img alt="Class-diagram ilustration for Laravel Json-importer" src=https://raw.githubusercontent.com/rstriquer/laravel-json-importer/main/docs/workflow.png width=800px />


# How to use

Afther installing the project you can run ```./artisan help import:customerFile``` for more information.

## Pre requisitos
* docker-compose
* php 8.0
* Composer 2

## How to install

Run the following command sequence:
* clone the project using git
* copy .env.example to .env and set the values
* Set up your database (read the "set the database" item for details)
* run ```./artisan migrate:install```

## Set the database

To use the system it is necessary to have a database. The project provides a docker instance to be used with docker-composer.

We recommend using MySQL 5.7 or higher. Previous versions will generate errors in the laravel project's default migration files;

To use docker just run the sequence below:

```bash
mysql -h 127.0.0.1 -u root -p123456 -e "CREATE DATABASE json_import; GRANT ALL PRIVILEGES ON json_import.* TO 'myuser'@'%';"
mysql -h 127.0.0.1 -u root -p123456 -e "GRANT ALL PRIVILEGES ON json_import.* TO 'myuser'@'%'"
```

PS: User "myuser" is created by docker-composer according to .env file

* Recommended docker version: 20 (or higher)


## License

This is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
