---
title: "Docker compose"
description: "Installation using docker compose"
weight: 50
date: "2022-03-01"
lastmod: "2022-05-10"
---

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