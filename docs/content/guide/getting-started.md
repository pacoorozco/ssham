---
title: "Getting started"
description: "Quick start and guides for installing ssham on your preferred operating system."
weight: 100
date: "2022-03-01"
lastmod: "2022-03-01"
---

## Quick start
This will create several [Docker](https://www.docker.com/) containers to implement all SSHAM needs. A web server and a database server.

Prior to this installation, you **need to have installed** this software:

* [Docker](https://www.docker.com/)

1. Clone the repository locally

    ```Shell
    git clone https://github.com/pacoorozco/ssham.git ssham
    cd ssham
    ```
1. Install PHP dependencies with:

    > **NOTE**: You don't need to install neither _PHP_ nor _Composer_, we are going to use a [Composer image](https://hub.docker.com/_/composer/) instead.

    ```Shell
    docker run --rm --interactive --tty \
          --volume $PWD:/app \
          --user $(id -u):$(id -g) \
          composer install
    ```

1. Copy [`.env.example`](.env.example) to `.env`.

    > **NOTE**: You don't need to touch anything from this file. It works with default settings.

1. Start all containers with [Docker Compose](https://docs.docker.com/compose/)

    ```Shell
    docker-compose build
    docker-compose up -d
    ```
1. Seed database in order to play with some data

    ```Shell
    docker-compose exec app php artisan key:generate 
    docker-compose exec app php artisan migrate --seed
    ```
    
1. Point your browser to `http://localhost` and test **SSH Access Manager**. Enjoy!

    > **NOTE**: Default credentials are `admin/secret`.

## Manual installation

1. Clone the repository locally

    ```Shell
    git clone https://github.com/pacoorozco/ssham.git ssham
    cd ssham
    ```

1. Install PHP dependencies with [composer](http://getcomposer.org)

    ```Shell
    curl -s https://getcomposer.org/installer | php
    php composer.phar install
    ```

1. Copy [`.env.example`](.env.example) to `.env`.  

1. Modify the content of the `.env` file to put your settings, something like that:

    ```php
    DB_HOST='Your database host'
    DB_DATABASE='Your database name'
    DB_USERNAME='Your database user'
    DB_PASSWORD='Your database password'
    ```
1. Seed database in order to play with some data

    ```Shell
    php artisan key:generate 
    php artisan migrate --seed
    ```
1. Make sure `storage/` and `bootstrap/cache/` folders are writable by your web server. You can do it this way:

    ```Shell
    chmod -R 777 storage/ bootstrap/cache/
    ```

1. You can use the local PHP server to run the application.

    ```Shell
    php artisan serve --port=4000`
    ```

1. Your SSH Access Manager is not listening at `http://localhost:4000`. Enjoy!

    > **NOTE**: Default credentials are `admin/secret`.
