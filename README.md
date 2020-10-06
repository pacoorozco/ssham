# SSH Access Manager Web Interface

[![Build Status](https://travis-ci.com/pacoorozco/ssham.svg)](https://travis-ci.com/pacoorozco/ssham)
[![Scrutinizer](https://img.shields.io/scrutinizer/g/pacoorozco/ssham.svg?style=flat-square)](https://scrutinizer-ci.com/g/pacoorozco/ssham)
[![Code Coverage](https://scrutinizer-ci.com/g/pacoorozco/ssham/badges/coverage.png)](https://scrutinizer-ci.com/g/pacoorozco/ssham)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/803ad655-408a-469f-8389-8e5fe0338cec/mini.png)](https://insight.sensiolabs.com/projects/803ad655-408a-469f-8389-8e5fe0338cec)
[![License](https://img.shields.io/github/license/pacoorozco/ssham.svg)](https://github.com/pacoorozco/ssham/blob/master/LICENSE)
[![Laravel Version](https://img.shields.io/badge/Laravel-6.x-brightgreen)](https://laravel.com/docs)
[![GitHub release](https://img.shields.io/github/release/pacoorozco/ssham.svg?style=flat-square)](https://github.com/pacoorozco/ssham/releases)

## Presentation

SSH Access Manager is a comprehensive access security management platform that permits IT professionals to easily establish and maintain an enterprise-wide SSH access security solution from a central location.

It enables a team of system administrators to centrally manage and deploy SSH keys. This app is intended to be used in rather large environment where access to unix accounts are handled with SSH keys.

SSH Access Manager allows you to maintain user public keys. You can organise these keys with group of keys called keyring. Then SSH Access Manager will deploy the keys and/or key rings to specified unix accounts / groups / servers.

## Changelog

See our [CHANGELOG](CHANGELOG.md) file in order to know what changes are implemented in every version.

## How to test SSH Access Manager

This will create several [Docker](https://www.docker.com/) containers to implement all SSHAM needs. A web server and a database server.

Prior this installation, you **need to have installed** this software:

* [Docker](https://www.docker.com/)

1. Clone the repository locally

    ```bash
    $ git clone https://github.com/pacoorozco/ssham.git ssham
    $ cd ssham
    ```
1. Install PHP dependencies with:

    > **NOTE**: You don't need to install neither _PHP_ nor _Composer_, we are going to use a [Composer image](https://hub.docker.com/_/composer/) instead.

    ```bash
    $ docker run --rm --interactive --tty \
          --volume $PWD:/app \
          --user $(id -u):$(id -g) \
          composer install
    ```

1. Copy [`.env.example`](.env.example) to `.env`.

    > **NOTE**: You don't need to touch anything from this file. It works with default settings.

1. Start all containers with [Docker Compose](https://docs.docker.com/compose/)

    ```bash
    $ docker-compose build
    $ docker-compose up -d
    ```
1. Seed database in order to play with some data

    ```bash
    $ docker-compose exec app php artisan key:generate 
    $ docker-compose exec app php artisan migrate --seed
    ```
    
1. Point your browser to `http://localhost` and test **SSH Access Manager**. Enjoy!

    > **NOTE**: Default credentials are `admin/secret`.

## How to install SSH Access Manager

1. Clone the repository locally

    ```bash
    $ git clone https://github.com/pacoorozco/ssham.git ssham
    $ cd ssham
    ```

1. Install PHP dependencies with [composer](http://getcomposer.org)

    ```bash
    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar install
    ```

1. Copy [`.env.example`](.env.example) to `.env`.  

1. Modify the content of the `.env` file to put your settings, something like that:

    ```php
    APP_ENV=production
    APP_DEBUG=false
    APP_URL=https://my.domain.com
    DB_HOST='Your database host'
    DB_DATABASE='Your database name'
    DB_USERNAME='Your database user'
    DB_PASSWORD='Your database password'
    ```
1. Seed database in order to play with some data

    ```bash
    $ php artisan key:generate 
    $ php artisan migrate --seed
    ```
1. Make sure `storage/` and `bootstrap/cache/` folders are writable by your web server. You can do it this way:

    ```bash
    $ chmod -R 777 storage/ bootstrap/cache/
    ```

1. You can use the local PHP server to run the application.

    ```bash
    $ php artisan serve --port=4000`
    ```

1. Your SSH Access Manager is not listening at `http://localhost:4000`. Enjoy!

    > **NOTE**: Default credentials are `admin/secret`.

## Reporting issues

If you have issues with **SSH Access Manager**, you can report them with the [GitHub issues module](https://github.com/pacoorozco/ssham/issues).

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

**SSH Access Manager** is released as free software under [GPLv3](http://www.gnu.org/licenses/gpl-3.0.html)

## Authors

This app was original coded by **Paco Orozco** (paco _at_ pacoorozco.info) 

## Additional information
This application was born with a different interface on [Sourceforge](http://sourceforge.net/projects/ssham/).
