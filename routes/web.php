<?php
/**
 * SSH Access Manager - SSH keys management solution.
 *
 * Copyright (c) 2017 - 2019 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2017 - 2019 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 * @link        https://github.com/pacoorozco/ssham
 */

use App\Http\Controllers\ControlRuleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HostController;
use App\Http\Controllers\HostgroupController;
use App\Http\Controllers\KeyController;
use App\Http\Controllers\KeygroupController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'auth'], function () {
    Route::get('/',
        [HomeController::class, 'index'])
        ->name('home');

    Route::post('search',
        [SearchController::class, 'index'])
        ->name('search');

    Route::get('logs',
        [LogController::class, 'index'])
        ->name('logs');
});

/* ------------------------------------------
 * Authentication routes
 *
 * Routes to be authenticated
 *  ------------------------------------------
 */
Auth::routes([
    'register' => false,  // User registration
    'verify' => false, // E-mail verification
]);

/**
 * ------------------------------------------
 * Users
 * ------------------------------------------.
 */
// DataTables Ajax route.
Route::middleware(['ajax'])
    ->get('users/data',
        [UserController::class, 'data'])
    ->name('users.data');

// Delete confirmation route - uses the show/details view.
Route::get('users/{user}/delete',
    [UserController::class, 'delete'])
    ->name('users.delete');

// Pre-baked resource controller actions for index, create, store,
// show, edit, update, destroy
Route::resource('users', UserController::class);

/**
 * ------------------------------------------
 * Keys
 * ------------------------------------------.
 */
// DataTables Ajax route.
Route::middleware(['ajax'])
    ->get('keys/data',
        [KeyController::class, 'data'])
    ->name('keys.data');

// Delete confirmation route - uses the show/details view.
Route::get('keys/{key}/delete',
    [KeyController::class, 'delete'])
    ->name('keys.delete');

// Download a private key.
Route::get('keys/{key}/download',
    [KeyController::class, 'downloadPrivateKey'])
    ->name('keys.download');

// Pre-baked resource controller actions for index, create, store,
// show, edit, update, destroy
Route::resource('keys', KeyController::class);

/**
 * ------------------------------------------
 * Hosts
 * ------------------------------------------.
 */
// DataTables Ajax route.
Route::middleware(['ajax'])
    ->get('hosts/data',
        [HostController::class, 'data'])
    ->name('hosts.data');

// Delete confirmation route - uses the show/details view.
Route::get('hosts/{host}/delete',
    [HostController::class, 'delete'])
    ->name('hosts.delete');

// Pre-baked resource controller actions for index, create, store,
// show, edit, update, destroy
Route::resource('hosts', HostController::class);

/**
 * ------------------------------------------
 * Key Groups
 * ------------------------------------------.
 */
// DataTables Ajax route.
Route::middleware(['ajax'])
    ->get('keygroups/data',
        [KeygroupController::class, 'data'])
    ->name('keygroups.data');

// Delete confirmation route - uses the show/details view.
Route::get('keygroups/{keygroup}/delete',
    [KeygroupController::class, 'delete'])
    ->name('keygroups.delete');

// Pre-baked resource controller actions for index, create, store,
// show, edit, update, destroy
Route::resource('keygroups', KeygroupController::class);

/**
 * ------------------------------------------
 * Host Groups
 * ------------------------------------------.
 */
// DataTables Ajax route.
Route::middleware(['ajax'])
    ->get('hostgroups/data',
        [HostgroupController::class, 'data'])
    ->name('hostgroups.data');

// Delete confirmation route - uses the show/details view.
Route::get('hostgroups/{hostgroup}/delete',
    [HostgroupController::class, 'delete'])
    ->name('hostgroups.delete');

// Pre-baked resource controller actions for index, create, store,
// show, edit, update, destroy
Route::resource('hostgroups', HostgroupController::class);

/**
 * ------------------------------------------
 * Control Rules
 * ------------------------------------------.
 */
// Datatables Ajax route.
Route::middleware(['ajax'])
    ->get('rules/data',
        [ControlRuleController::class, 'data'])
    ->name('rules.data');

// Pre-baked resource controller actions for index, create, store,
// show, edit, update, destroy
Route::resource('rules', ControlRuleController::class);

/**
 * ------------------------------------------
 * Settings
 * ------------------------------------------.
 */
Route::get('settings',
    [SettingsController::class, 'index'])
    ->name('settings.index');

Route::put('settings',
    [SettingsController::class, 'update'])
    ->name('settings.update');
