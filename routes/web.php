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

Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);
Route::get('/home', ['as' => 'home', 'uses' => 'HomeController@index']);
Route::post('/search', ['as' => 'search', 'uses' => 'HomeController@search']);

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
Route::get('users/data',
    ['as' => 'users.data', 'uses' => 'UserController@data'])
    ->middleware('ajax');
// Delete confirmation route - uses the show/details view.
Route::get('users/{user}/delete',
    ['as' => 'users.delete', 'uses' => 'UserController@delete']);
// Pre-baked resource controller actions for index, create, store,
// show, edit, update, destroy
Route::resource('users', 'UserController');

/**
 * ------------------------------------------
 * Keys
 * ------------------------------------------.
 */
// DataTables Ajax route.
Route::get('keys/data',
    ['as' => 'keys.data', 'uses' => 'KeyController@data'])
    ->middleware('ajax');
// Delete confirmation route - uses the show/details view.
Route::get('keys/{key}/delete',
    ['as' => 'keys.delete', 'uses' => 'KeyController@delete']);
// Download a private key.
Route::get('keys/{key}/download',
    ['as' => 'keys.download', 'uses' => 'KeyController@downloadPrivateKey']);
// Pre-baked resource controller actions for index, create, store,
// show, edit, update, destroy
Route::resource('keys', 'KeyController');

/**
 * ------------------------------------------
 * Hosts
 * ------------------------------------------.
 */
// DataTables Ajax route.
Route::get('hosts/data',
    ['as' => 'hosts.data', 'uses' => 'HostController@data'])
    ->middleware('ajax');
// Delete confirmation route - uses the show/details view.
Route::get('hosts/{host}/delete', ['as' => 'hosts.delete', 'uses' => 'HostController@delete']);
// Pre-baked resource controller actions for index, create, store,
// show, edit, update, destroy
Route::resource('hosts', 'HostController');

/**
 * ------------------------------------------
 * Key Groups
 * ------------------------------------------.
 */
// DataTables Ajax route.
Route::get('keygroups/data',
    ['as' => 'keygroups.data', 'uses' => 'KeygroupController@data'])
    ->middleware('ajax');
// Delete confirmation route - uses the show/details view.
Route::get('keygroups/{keygroup}/delete', ['as' => 'keygroups.delete', 'uses' => 'KeygroupController@delete']);
// Pre-baked resource controller actions for index, create, store,
// show, edit, update, destroy
Route::resource('keygroups', 'KeygroupController');

/**
 * ------------------------------------------
 * Host Groups
 * ------------------------------------------.
 */
// DataTables Ajax route.
Route::get('hostgroups/data',
    ['as' => 'hostgroups.data', 'uses' => 'HostgroupController@data'])
    ->middleware('ajax');
// Delete confirmation route - uses the show/details view.
Route::get('hostgroups/{hostgroup}/delete', ['as' => 'hostgroups.delete', 'uses' => 'HostgroupController@delete']);
// Pre-baked resource controller actions for index, create, store,
// show, edit, update, destroy
Route::resource('hostgroups', 'HostgroupController');

/**
 * ------------------------------------------
 * Control Rules
 * ------------------------------------------.
 */
// Datatables Ajax route.
Route::get('rules/data',
    ['as' => 'rules.data', 'uses' => 'ControlRuleController@data'])
    ->middleware('ajax');
// Pre-baked resource controller actions for index, create, store,
// show, edit, update, destroy
Route::resource('rules', 'ControlRuleController');

/**
 * ------------------------------------------
 * Settings
 * ------------------------------------------.
 */
Route::get('settings', ['as' => 'settings.index', 'uses' => 'SettingsController@index']);
Route::put('settings', ['as' => 'settings.update', 'uses' => 'SettingsController@update']);
