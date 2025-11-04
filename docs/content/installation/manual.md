---
title: "Manual"
description: "Manual installation"
weight: 100
date: "2022-03-01"
lastmod: "2022-05-10"
---

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
    php artisan migrate:fresh --seed
    ```
1. Make sure `storage/` and `bootstrap/cache/` folders are writable by your web server. You can do it this way:

    ```Shell
    chmod -R 777 storage/ bootstrap/cache/
    ```

1. Configure crontab to run scheduled tasks (eg. cleaning audit logs)

    ```Shell
   crontab -e 
    ```
   
    Add this line to execute the scheduler:

    ```Shell
   echo "* * * * * cd $(pwd) && php artisan schedule:run >> /dev/null 2>&1" >>
    ```
1. You can use the local PHP server to run the application.

    ```Shell
    php artisan serve --port=4000`
    ```

1. Your SSH Access Manager is not listening at `http://localhost:4000`. Enjoy!

    > **NOTE**: Default credentials are `admin/secret`.
