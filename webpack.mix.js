/*
 * SSH Access Manager - SSH keys management solution.
 *
 * Copyright (c) 2017 - 2021 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 *  @author      Paco Orozco <paco@pacoorozco.info>
 *  @copyright   2017 - 2021 Paco Orozco
 *  @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 *  @link        https://github.com/pacoorozco/ssham
 */

const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */
mix.copyDirectory('node_modules/clipboard/dist', 'public/vendor/clipboard');
mix.copyDirectory('node_modules/admin-lte/dist', 'public/vendor/AdminLTE');

const adminLTEPlugins = {
    'node_modules/admin-lte/plugins/fontawesome-free': 'public/vendor/AdminLTE/plugins/fontawesome-free',
    'node_modules/admin-lte/plugins/jquery': 'public/vendor/AdminLTE/plugins/jquery',
    'node_modules/admin-lte/plugins/bootstrap': 'public/vendor/AdminLTE/plugins/bootstrap',
    'node_modules/admin-lte/plugins/datatables-bs4': 'public/vendor/AdminLTE/plugins/datatables-bs4',
    'node_modules/admin-lte/plugins/datatables': 'public/vendor/AdminLTE/plugins/datatables',
    'node_modules/admin-lte/plugins/select2': 'public/vendor/AdminLTE/plugins/select2',
    'node_modules/admin-lte/plugins/icheck-bootstrap': 'public/vendor/AdminLTE/plugins/icheck-bootstrap',
};

for (let directory in adminLTEPlugins) {
    mix.copyDirectory(directory, adminLTEPlugins[directory]);
}
