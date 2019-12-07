# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).

## Unreleased
> **Note**: This application is now using [Laravel 6](https://laravel.com/docs).

### Added
- Copyright under [GPLv3 license](http://www.gnu.org/licenses/gpl-3.0.html) has been added to source code.
### Changed
- `symfony/translation` has been downgraded to version 4.3.8 due to a bug in latest releases, [see it here](https://github.com/laravel/framework/issues/30655).
- **Important**: This application has been upgraded to [Laravel 6](https://laravel.com/docs).

### Fixed
- Fix PHPUnit configuration.
- Fix CI configuration. Now it's using [travis-ci.com](https://travis-ci.com/pacoorozco/ssham).
- Fix `web` docker configuration in order to allow `docker-compose` run. ([#18][i18])

[i18]: https://github.com/pacoorozco/ssham/issues/18

## 0.5.0 - 2018-08-10

## 0.4.0 - 2017-07-29
### Changed
- The most important change is a licensing one. *SSH Access Manager* is now licensed under [GPLv3 license](http://www.gnu.org/licenses/gpl-3.0.html).
- This release adds support for docker. Now you can test this application running a docker, see [README](README.md) for more information.

