---
title: "Laravel Sail"
description: "Installation using Laravel Sail"
weight: 50
date: "2022-03-01"
lastmod: "2022-05-10"
---

[Laravel Sail](https://laravel.com/docs/9.x/sail) is a light-weight command-line interface for interacting with
Laravel's default Docker development environment. This will create several containers to implement all SSHAM needs. An
application server and a database server.

Prior to this installation, you **need to have installed** this software:

* [Docker](https://www.docker.com/)

1. Clone the repository locally

    ```Shell
    git clone https://github.com/pacoorozco/ssham.git ssham
    cd ssham
    ```

2. Copy [`.env.example`](.env.example) to `.env`.

   > **NOTE**: You don't need to touch anything from this file. It works with default settings.

3. Install PHP dependencies with:

   > **NOTE**: You don't need to install neither _PHP_ nor _Composer_, we are going to use
   a [Composer image](https://hub.docker.com/_/composer/) instead.

    ```Shell
    docker run --rm \                  
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs
    ```

4. Start all containers with the `sail` command.

    ```Shell
    ./vendor/bin/sail up -d
    ```

5. Seed database in order to play with some data

    ```Shell
   # Running Artisan commands within Laravel Sail...
   sail artisan key:generate 
   sail artisan migrate:fresh --seed
    ```

1. Point your browser to `http://localhost` and test **SSH Access Manager**. Enjoy!

   > **NOTE**: Default credentials are [here]({{< ref "/using-ssham/auth" >}} "Authentication").
