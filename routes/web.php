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
 *
 * @link        https://github.com/pacoorozco/ssham
 */

use App\Http\Controllers\AuditController;
use App\Http\Controllers\AuditDataTablesController;
use App\Http\Controllers\ControlRuleController;
use App\Http\Controllers\ControlRuleDataTablesController;
use App\Http\Controllers\DownloadPrivateKeyController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HostController;
use App\Http\Controllers\HostDataTablesController;
use App\Http\Controllers\HostgroupController;
use App\Http\Controllers\HostgroupDataTablesController;
use App\Http\Controllers\KeyController;
use App\Http\Controllers\KeyDataTablesController;
use App\Http\Controllers\KeygroupController;
use App\Http\Controllers\KeygroupDataTablesController;
use App\Http\Controllers\PersonalAccessTokenController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserDataTablesController;
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

Route::middleware(['auth'])->group(function () {
    Route::get('/', HomeController::class)
        ->name('home');

    Route::get('search', SearchController::class)
        ->name('search');

    /**
     * ------------------------------------------
     * Audit
     * ------------------------------------------.
     */
    Route::get('audit', AuditController::class)
        ->name('audit');

    // DataTables Ajax route.
    Route::middleware(['ajax'])
        ->get('audit/data', AuditDataTablesController::class)
        ->name('audit.data');

    /**
     * ------------------------------------------
     * Users
     * ------------------------------------------.
     */
    // DataTables Ajax route.
    Route::middleware(['ajax'])
        ->get('users/data', UserDataTablesController::class)
        ->name('users.data');

    // Pre-baked resource controller actions for index, create, store,
    // show, edit, update, destroy
    Route::resource('users', UserController::class);

    /**
     * ------------------------------------------
     * Personal Access Tokens
     * ------------------------------------------.
     */
    Route::resource('users.tokens', PersonalAccessTokenController::class)
        ->shallow()
        ->only(['index', 'create', 'store', 'destroy']);

    /**
     * ------------------------------------------
     * Keys
     * ------------------------------------------.
     */
    // DataTables Ajax route.
    Route::middleware(['ajax'])
        ->get('keys/data', KeyDataTablesController::class)
        ->name('keys.data');

    // Download a private key.
    Route::get('keys/{key}/download', DownloadPrivateKeyController::class)
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
        ->get('hosts/data', HostDataTablesController::class)
        ->name('hosts.data');

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
        ->get('keygroups/data', KeygroupDataTablesController::class)
        ->name('keygroups.data');

    Route::resource('keygroups', KeygroupController::class);

    /**
     * ------------------------------------------
     * Host Groups
     * ------------------------------------------.
     */
    // DataTables Ajax route.
    Route::middleware(['ajax'])
        ->get('hostgroups/data', HostgroupDataTablesController::class)
        ->name('hostgroups.data');

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
        ->get('rules/data', ControlRuleDataTablesController::class)
        ->name('rules.data');

    // Pre-baked resource controller actions for index, create, store,
    // show, edit, update, destroy
    Route::resource('rules', ControlRuleController::class)
        ->only(['index', 'create', 'store', 'destroy']);

    /**
     * ------------------------------------------
     * Settings
     * ------------------------------------------.
     */
    Route::get('settings',
        [SettingsController::class, 'index'])
        ->name('settings.index');

    Route::get('settings/edit',
        [SettingsController::class, 'edit'])
        ->name('settings.edit');

    Route::put('settings',
        [SettingsController::class, 'update'])
        ->name('settings.update');
});
