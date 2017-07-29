# SSH Access Manager Web Interface

[![Scrutinizer](https://img.shields.io/scrutinizer/g/pacoorozco/ssham.svg?style=flat-square)](https://scrutinizer-ci.com/g/pacoorozco/ssham)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/803ad655-408a-469f-8389-8e5fe0338cec.svg)](https://insight.sensiolabs.com/projects/803ad655-408a-469f-8389-8e5fe0338cec)
[![GitHub license](https://img.shields.io/github/license/pacoorozco/ssham.svg)](https://github.com/pacoorozco/ssham/blob/master/LICENSE)
[![GitHub release](https://img.shields.io/github/tag/pacoorozco/ssham.svg?style=flat-square)](https://github.com/pacoorozco/ssham/releases)
[![License](https://img.shields.io/github/license/pacoorozco/ssham.svg)](https://github.com/pacoorozco/ssham/blob/master/LICENSE)

## Description

[SSH Access Manager](http://sourceforge.net/projects/ssham/) is a comprehensive access security management platform that permits IT professionals to easily establish and maintain an enterprise-wide SSH access security solution from a central location.

It enables a team of system administrators to centrally manage and deploy SSH keys. This app is intended to be used in rather large environnements where access to unix accounts are handled with SSH keys.

SSH Access Manager allows you to maintain user public keys. You can organise these keys with group of keys called keyring. Then SSH Access Manager will deploy the keys and/or keyrings to specified unix accounts / groups / servers.


## How to test ProBIND

This will create several [Docker](https://www.docker.com/) containers to implement all SSHAM needings. A web server, a database server and a Redis server.

Prior this installation, you **need to have installed** this software:

* [Docker](https://www.docker.com/)
* [Docker Compose](https://docs.docker.com/compose/)

1. Clone the repository locally

    ```bash
    $ git clone https://github.com/pacoorozco/ssham.git ssham
    ```
2. Start all containers with [Docker Compose](https://docs.docker.com/compose/)

    ```bash
    $ cd ssham/docker
    $ docker-compose build
    $ docker-compose up -d
    ```
3. Seed database in order to play with some data


    ```bash
    $ docker exec docker_web_1 /setup.sh 
    ```
4. Point your browser to `http://localhost`. Your credential will be `admin/admin` or `user/user`.

Enjoy!

## How to install
### Step 1: Get the code

```bash
$ git clone https://github.com/pacoorozco/ssham.git ssham
```

### Step 2: Use Composer to install dependencies

```bash
$ cd ssham
$ curl -s http://getcomposer.org/installer | php
$ php composer.phar install
```

### Step 3: Configure Environments

You need to create a configuration file called ***.env*** on the root folder, where ***composer.json*** is placed:

```bash
$ cp .env.example .env
```
You need to modify the content of this file in order to put your information, something like that:

```php
DB_HOST='Your database host'
DB_DATABASE='Your database name'
DB_USERNAME='Your database user'
DB_PASSWORD='Your database password'
```
You need to generate a secure APP_KEY doing this:

```bash
$ php artisan key:generate
Application key [mMNylItCyQHB7UBXzffnkgYlrc1d73La] set successfully.
```
### Step 4: Populate Database
Run these commands to create and populate tables:

```bash
$ php artisan migrate
$ php artisan db:seed
```
Once the migrations are run and you've seeded the database -  admin:admin account will be created as well as default permissions.

### Step 5: Make sure app/storage is writable by your web server.

You can do it this way:

```bash
$ chmod -R 777 app/storage
```

You can use the local PHP server to run the application.

```bash
$ php artisan serve --port=4000`
```

## License

This app was original coded by **Paco Orozco** (paco _at_ pacoorozco.info) and it's released as free software under is released as free software under [GPLv3](http://www.gnu.org/licenses/gpl-3.0.html).

## Additional information
Please consult additional interfaces on [Sourceforge](http://sourceforge.net/projects/ssham/).

