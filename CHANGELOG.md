# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/) and this project adheres to [Semantic Versioning](https://semver.org/).

## 0.6.1
### Added
- Refactor code to add Unit tests to improve code coverage.
- Add two values to `config/auth.php` to configure Login throttling: `auth.login.max_attempts` and `auth.login.decay_minutes`.

## 0.6.0
> **Note**: This application is now using [Laravel 6](https://laravel.com/docs).

### Added
- Copyright under [GPLv3 license](http://www.gnu.org/licenses/gpl-3.0.html) has been added to source code.
- [Scrutinizer](https://scrutinizer-ci.com/g/pacoorozco/ssham/) inside CI/CD process.

### Changed
- Updated configuration for Travis-ci.com, Scrutinizer and Symfony Insight (setting PHP version to 7.2)
- **Important**: This application has been upgraded to [Laravel 6](https://laravel.com/docs). A lot of refactors has been done in order to adopt Laravel 6.x best practices. ([#21][i21])
- [AdminLTE](https://adminlte.io/themes/v3/index.html) has been upgraded to version 3 to use [bootstrap 4](https://getbootstrap.com/docs/4.4/getting-started/introduction/).
- All CSS and JS assets are now vendored and distributed with this code. It will avoid lack of assets in case of source deprecation. It uses webpack to vendor dependencies.

### Fixed
- Fix PHPUnit configuration.
- Fix CI configuration. Now it's using [travis-ci.com](https://travis-ci.com/pacoorozco/ssham).
- Fix `web` docker configuration in order to allow `docker-compose` run. ([#18][i18])

[i18]: https://github.com/pacoorozco/ssham/issues/18
[i21]: https://github.com/pacoorozco/ssham/issues/21

## 0.5.0 - 2018-08-10
- N/A

## 0.4.0 - 2017-07-29
### Changed
- The most important change is a licensing one. *SSH Access Manager* is now licensed under [GPLv3 license](http://www.gnu.org/licenses/gpl-3.0.html).
- This release adds support for docker. Now you can test this application running a docker, see [README](README.md) for more information.

