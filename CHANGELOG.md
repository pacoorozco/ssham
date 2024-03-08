# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/) and this project adheres to [Semantic Versioning](https://semver.org/).

## Unreleased

## 0.19.3 - 2024-03-08
### Changed
- Bump `spatie/laravel-permission` to 6.4.0
- Bump `yajra/laravel-datatables-oracle` to 10.11.4
- Bump `bensampo/laravel-enum` to 6.10.0


## 0.19.2 - 2024-02-10
### Changed
- Bump `phpseclib/phpseclib` to 3.0.35
- Bump ` larastan/larastan` to 2.8.1
- Bump `bensampo/laravel-enum` to 6.8.0
- Bump `laravel/sanctum` to 3.3.3
- Bump `laravel/sail` to 1.27.3
- Bump `laravel/framework` to 10.43.0
- Bump `laravel/ui` to 4.4.0
- Bump `yajra/laravel-datatables-oracle` to 10.11.3
- Bump `brianium/paratest` to 7.4.1
- Bump `doctrine/dbal` to 3.8.1
- Bump `laravel/pint` to 10.13.10
- Bump `laravel/sail` 1.27.3
- Bump `phpunit/phpunit` 10.5.10
- Bump `spatie/laravel-ignition` to 2.4.2
### Fixed
- Error when updating own profile wo/ password change  ([#457][i457])

[i457]: https://github.com/pacoorozco/ssham/issues/457

## 0.19.1 - 2024-01-13
### Changed
- Bump `aglipanci/laravel-pint-action` to version 2.3.1
- Bump `yajra/laravel-datatables-oracle` to version 10.11.3
- [DEV] Bump `phpunit/phpunit` to version 10.5.5
- Bump `laravel/framework` to version 10.40.0
- Bump `laravel/ui` to version 4.3.0
- Bump `spatie/laravel-permission` to version 6.3.0

## 0.19.0 - 2023-12-13


### Changed
- **Important**: The required minimum version of PHP is v8.2.
- Bump `bensampo/laravel-enum` from 6.6.0 to 6.7.0
- Bump `laravel-json-api/laravel` from 3.0.0 to 3.2.0
- Bump `laravel/framework` from 10.17.0 to 10.37.0
- Bump `laravel/sanctum` from 3.2.5 to 3.3.2
- Bump `laravel/ui` from 4.2.2 to 4.2.3
- Bump `pacoorozco/openssh` from 0.5.0 to 0.5.1
- Bump `phpseclib/phpseclib` from 3.0.19 to 3.0.34
- Bump `spatie/laravel-permission` from 5.10.0 to 5.11.0
- Bump `yajra/laravel-datatables-oracle` from 10.4.2 to 10.11.2

## 0.18.1 - 2023-10-06

### Changed
- Bump `yajra/laravel-datatables-oracle` from 10.4.0 to 10.4.2
- Bump `laravel/ui` from 4.2.1 to 4.2.2
- Bump `spatie/laravel-ignition` from 2.1.0 to 2.1.3
- Bump `bensampo/laravel-enum` from 6.3.1 to 6.6.0
- Bump `doctrine/dbal` from 3.6.2 to 3.6.5
- [CI] Bump `aglipanci/laravel-pint-action` from 2.1.0 to 2.3.0
- [CI] Bump `fakerphp/faker` from 1.21.0 to 1.23.0
- [CI] Bump `laravel/sail` from 1.21.5 to 1.22.0
- [CI] Bump `brianium/paratest` from 7.1.3 to 7.2.3
- [CI] Bump `laravel/pint` from 1.10.0 to 1.10.5

## 0.18.0 - 2023-05-16

### Added
- Control over the public key comment. Users can customize it with the key's name. ([#353][i353])
- Refactor to improve performance when generation `authorized_keys` files.

### Changed
- Dependencies are updated to the latest releases.
- Replace `laravelcollective/html` by `laravel-form-components` package. ([#394][i394])
- [CI] Use `RefreshDatabase` to enable refresh after each test.
- [CI] Replace deprecated create release action

### Fixed
- Fix broken link on the CONTRIBUTING document.
- [CI] Fix deprecation message on GHA

### Removed
- Possibility to set `Deny` rules. Denial is intended by default.

[i353]: https://github.com/pacoorozco/ssham/issues/353
[i394]: https://github.com/pacoorozco/ssham/issues/394

## 0.17.0 - 2023-03-09
> NOTE: This release has **non-backwards compatible** changes. It may include some changes in the database tables.

### Changed
- **Important**: The required minimum version of PHP is v8.1.
- **Important**: This application has been upgraded to [Laravel 10.x](https://laravel.com/docs).
- **Important**: Database schema has been modified in a **non-backwards compatible way**.
    - The `password_resets` table renamed to `password_resets_tokens`.
- Test running against a real database instead of memory (SQLite).

### Removed
- Unused `fuitcake/cors` dependency.

## 0.16.0 - 2022-10-14

### Added
- Support for `ed25519` public keys. ([#346][i346])

[i346]: https://github.com/pacoorozco/ssham/issues/346

## 0.15.2 - 2022-06-13

### Changed
- Updated dependencies to the latest versions.
- The bastion's public key will be created from the private key submitted in the `Settings` page. ([#318][i318])
- The unused temporary dir has been removed from the `Settings` page. ([#165][i165]) 

### Fixed
- Everytime a user logs in, the model is updated (some date) on audit. ([#163][i163])
- PHPStan message about Yajra DataTables. ([#331][i331])
- Fix flaky test in `Feature/Http/Controllers/AuditDataTablesControllerTest.php`.

[i318]: https://github.com/pacoorozco/ssham/issues/318
[i165]: https://github.com/pacoorozco/ssham/issues/165
[i163]: https://github.com/pacoorozco/ssham/issues/163
[i331]: https://github.com/pacoorozco/ssham/issues/331

## 0.15.1 - 2022-05-27

### Fixed
- Stack trace is logged when server could not be reached bug. ([#313][i313])

[i313]: https://github.com/pacoorozco/ssham/issues/313

## 0.15.0 - 2022-05-27

### Added
- Testing for validation rules to improve test coverage.
- Testing for the Settings controller.
- Test roles and permission in all the controllers to ensure security.

### Changes
- Updates dependencies.
- Moves language files to the Laravel 9.x default folder.
- Update docker versions to use latest ones.

### Fixed
- Use of internal Actions instead of Jobs for synchronous changes.
- ssh connection not work. ([#307][i307])
- `ssham:send` command was not working, several errors were found. ([#305][i305])

[i307]: https://github.com/pacoorozco/ssham/issues/307
[i305]: https://github.com/pacoorozco/ssham/issues/305

## 0.14.5 - 2022-04-06

### Changed
- Update dependencies to fix some security vulnerabilities.

## 0.14.4 - 2022-03-08

> Migrated to [Laravel 9.x](https://laravel.com/docs/9.x/releases) to take benefit of the new features. ([#243][i243])
### Changed
- Bump `fruitcake/laravel-cors` to `v3.0.0`.
- Bump `larapacks/setting` to `v3.0.1`.
- Promote `spatie/laravel-ignition` instead of `facade/ignition`.
- Bump `laravel/framework` to `v9.2`.
- Bump `bensampo/laravel-enum` to `v5.1`.
- Bump `guilhermegonzaga/presenter` to `v1.0.6`.
- Bump `spatie/laravel-permission` to `v5.5.0`.
- Update dev dependencies
    - Bump `nunomaduro/collision` to `v6.1`.
### Removed
- Dependency `fideloper/proxy`, it's part of Laravel 9 core. [Details](https://github.com/fideloper/TrustedProxy/issues/152).

[i243]: https://github.com/pacoorozco/ssham/issues/243

## 0.14.3 - 2022-02-03

### Fixed
- Validation errors when admin tries to edit itself. ([#235][i235])
### Changed
- Bump `laravel/framework` to `v8.82.0`.
- Bump `fruitcake/laravel-cors` to `v2.0.5`.
- Bump `spatie/laravel-activitylog` to `v4.4.0`.
- Bump `spatie/laravel-searchable` to `v1.11.0`.
- Bump `yajra/laravel-datatables-oracle` to `v9.19.0`.
- Bump `laravel-json-api/laravel` to `v1.1.0`.
- Bump `laravel/sanctum` to `v2.14.0`.
- Bump `phpseclib/phpseclib` to `v3.0.13`.
- Bump `laravel/ui` to `v3.4.2`.
- Bump `pacoorozco/openssh` to `v0.2.1`.
- Update dev dependencies
    - Bump `facade/ignition` to `v2.17.4`.
    - Bump `fakerphp/faker` to `v1.19.0`.
    - Bump `laravel-json-api/testing` to `v1.0.0`.
    - Bump `mockery/mockery` to `v1.5.0`.
    - Bump `nunomaduro/collision` to `v5.11.0`.
    - Bump `phpunit/phpunit` to `v9.5.13`.
    - Bump `doctrine/dbal` to `v3.3.1`.

[i235]: https://github.com/pacoorozco/ssham/issues/235

## 0.14.2 - 2021-12-02

### Changed
- Bump `laravel/framework` to `v8.74`.
- Bump `laravel/sanctum` to `v2.12.2`.
- Bump `laravel/ui` to `v3.4.0`.
- Bump `phpseclib/phpseclib` to `v3.0.12`.
- Bump `spatie/laravel-activitylog` to `v4.3.1`.
- Bump `spatie/laravel-permission` to `v4.4.3`.
- Bump `yajra/laravel-datatables-oracle` to `v9.18.2`.
### Fixed
- Typo in the `docker-compose.yml`.
- Scrutinizer setup to use at least PHP 8.0.2.

## 0.14.1 - 2021-10-08

### Changed
- Bump `bensampo/laravel-enum` to `v3.4.2`.
- Bump `laravel-json-api/laravel` to `v1.0.0`.
- Bump `laravel/framework` to `v8.63`.
- Bump `laravel/sanctum` to `v2.11.2`.
- Bump `phpseclib/phpseclib` to `v3.0.10`.
- Bump `spatie/laravel-activitylog` to `v4.2.0`.
- Bump `spatie/laravel-permission` to `v4.4.1`.
- Bump `yajra/laravel-datatables-oracle` to `v9.18.1`.
- Update dev dependencies
  - Bump `facade/ignition` to `v2.14.0`.
  - Bump `fakerphp/faker` to `v1.16.0`.
  - Bump `laravel-json-api/testing` to `v1.0.0`.
  - Bump `mockery/mockery` to `v1.4.4`.
  - Bump `nunomaduro/collision` to `v5.10.0`.
  - Bump `phpunit/phpunit` to `v9.5.10`.
  - Bump `doctrine/dbal` to `v3.1.3`.

## 0.14.0 - 2021-06-30
### Added
- Reset password endpoint, which allow users to change its own password. ([#112][i112])
- Roles and permissions: ability to define users w/ different capabilities. ([#113][i113])
- **OpenSSH** are used by default know. We were using raw [RSA keys](https://phpseclib.com/docs/rsa) before.
- Tests to improve code coverage, covering several fixed bugs.
### Changed
- Design of almost all pages to improve UX.
- Improved code for better DX: less doc block, better var and method names. ([#166][i166])
- Application will use **OpenSSH** keys by default, instead of RSA ones.
- Migrate code to use [phpseclib](https://phpseclib.com/) `v3`.
- Bump `bensampo/laravel-enum` to `v3.4`.
- Bump `laravel/framework` to `v8.49`.
- Bump `laravel/ui` to `v3.3`.
- Bump `spatie/laravel-activitylog` to `v4.0`.
- Bump `spatie/laravel-searchable` to `v1.10`.
### Removed
- Unused endpoints and methods.

[i112]: https://github.com/pacoorozco/ssham/issues/112
[i113]: https://github.com/pacoorozco/ssham/issues/113
[i166]: https://github.com/pacoorozco/ssham/issues/166

## 0.13.0 - 2021-05-22

### Added
- Validation for `usernames` following POSIX definition, [The Open Group Base Specifications Issue 7, 2018 edition](https://pubs.opengroup.org/onlinepubs/9699919799/basedefs/V1_chap03.html#tag_03_437).
- `KeyController` tests for CRUD operations.
- Add **Personal Access Tokens** to users to implement API authentication based on [Bearer tokens](https://laravel.com/docs/8.x/http-client#bearer-tokens).
- API accesses to Hosts and Hostgroups. It follows [{json:api}](https://jsonapi.org/) specification. ([#110][i110])
### Changed
- Settings package to `larapacks/setting`.
- Update dependencies to latest versions.
- Bump `AdminLTE` from `v3.0.5` to `v3.1.0`. 
- Bump `fruitcake/laravel-cors` to `v2.0.4`.
- Bump `laravel/framework` to `v8.41`.
- Bump `laravel/sanctum` to `v2.11`.
- Bump `phpseclib/phpseclib` to `v2.0.31`.
- Code to honor PHP 8.0 best practices.
- Refactor code to make it more readable (implementing Jobs and Observers).
### Fixed
- Bug using `php artisan ssham:send` command.
- Scrutinizer findings to improve code quality. 
### Removed
- PHP 7.4 support. This application will need PHP 8.0 or higher to run. ([#101][i101])
- Remove unused `AdminLTE` plugins from `public/vendor/AdminLTE` to reduce vulnerability surface.
- Remove unused `jquery-ujs` library.

## 0.12.0 - 2021-02-12
### Added
- Log to audit all changes made in the application. ([#86][i86])

### Changed
- Dashboard appearance to make include audit log. ([#85][i85])

### Fixed
- Some cookies are misusing the recommended “SameSite“ attribute. ([#92][i92])
- Error when creating a Control Rule. ([#89][i89])

[i86]: https://github.com/pacoorozco/ssham/issues/86
[i85]: https://github.com/pacoorozco/ssham/issues/85
[i92]: https://github.com/pacoorozco/ssham/issues/92
[i89]: https://github.com/pacoorozco/ssham/issues/89
[i101]: https://github.com/pacoorozco/ssham/issues/101
[i110]: https://github.com/pacoorozco/ssham/issues/110

## 0.11.0 - 2021-01-30

### Fixed
- Migrated to [Laravel 8.x](https://laravel.com/docs/8.x/releases) to take benefit of the new features. ([#81][i81])

[i81]: https://github.com/pacoorozco/ssham/issues/81

## 0.10.0 - 2021-01-27

### Added
- Audit log: Actions are logged and shown on the Dashboard page.
- A docker with SFTP to test **ssham** locally. ([#73][i73])
### Changed
- Updates `php` dependency to version `7.4`.
- Updates `akaunting/setting` to `v1.2`.
- Updates `laravel/framework` to `v6.20.15`.
- Updates `spatie/laravel-searchable` to `v1.9`.
- Updates `yajra/laravel-datatables-oracle` to `v9.15`.
- Updates `fideloper/proxy` to `v4.4`.
### Fixed
- Warning when building docker images. ([#74][i74])
### Removed
- Removes `laravel/tinker`, it was not used.

[i73]: https://github.com/pacoorozco/ssham/issues/73
[i74]: https://github.com/pacoorozco/ssham/issues/74

## 0.9.0 - 2020-12-19

### Changed
- Docker creation has been changed to embed `composer` inside the docker.
- Updated `npm` dependencies.

## 0.8.1 - 2020-10-16

### Added
- Documentation site available at [ssham.pacoorozco.info](https://ssham.pacoorozco.info).
### Changed
- Update dependencies to the latest versions.
### Fixed
- Namespaces for Feature tests has been fixed to `namespace Tests\Feature\...`.
- Namespace for Helper has been set to `App\Helpers`.
- Trait `UsesUUID` was not PSR-4 compliant.
- Issue: Keygroups error when the artisan job is executed. ([#55][i55])

[i55]: https://github.com/pacoorozco/ssham/issues/55

## 0.8.0 - 2020-09-01
### Added
- Configuration for putting this application behind a Load Balancer. See `config/trustedproxy.php` for more details.
### Fixed
- Update vulnerable packages: `lodash`, `elliptic`, `dot-prop` and `serialize-javascript`.

## 0.7.1 - 2020-06-23

### Added
- Port and Authorized keys file path could be configured at `Host` level. On settings section you can set the default values for them.
- Script to release versions: `bumpversion.sh`.
### Changed
- Minimum supported version is PHP 7.4.
### Removed
- Some unused code and routes.

## 0.7.0
> **NOTE**: This version includes **major changes**, that are **not backwards** compatible. If you were using a previous version, please set-up a new database.
### Changed
- A new `Key` mode has been added. `User` model will be used to manage SSHAM administrators, while `Key` will host the SSH Keys being pushed to the `Host`.
- Change `id` type to UUID to implement more security in regards keys. UUID is more difficult to guess.
### Removed
- `Roles` were partially implemented. It has been removed to simplify the application.
- `FileEntry` has been removed. Private key could be downloaded **only once** from `key` show option.

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

