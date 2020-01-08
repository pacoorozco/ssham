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


use Illuminate\Support\Facades\Route;

Route::get('/home', ['as' => 'home', 'uses' => 'HomeController@index']);

/*Route::controller('auth', 'Auth\AuthController', [
    'getLogin' => 'login',
    'getLogout' => 'logout',
]);*/

Auth::routes(['register' => false]);

/**
 * ------------------------------------------
 * File Downloader
 * ------------------------------------------
 */
Route::get('file/{filename}', ['as' => 'file.download', 'uses' => 'FileEntryController@get']);

/**
 * ------------------------------------------
 * Users
 * ------------------------------------------
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
 * Hosts
 * ------------------------------------------
 */
// Datatables Ajax route.
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
 * Usergroups
 * ------------------------------------------
 */
// Datatables Ajax route.
Route::get('usergroups/data',
    ['as' => 'usergroups.data', 'uses' => 'UsergroupController@data'])
    ->middleware('ajax');
// Delete confirmation route - uses the show/details view.
Route::get('usergroups/{usergroup}/delete', ['as' => 'usergroups.delete', 'uses' => 'UsergroupController@delete']);
// Pre-baked resource controller actions for index, create, store,
// show, edit, update, destroy
Route::resource('usergroups', 'UsergroupController');

/**
 * ------------------------------------------
 * Hostgroups
 * ------------------------------------------
 */
// Datatables Ajax route.
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
 * Rules
 * ------------------------------------------
 */
// Datatables Ajax route.
Route::get('rules/data',
    ['as' => 'rules.data', 'uses' => 'RuleController@data']);
    //->middleware('ajax');
// Pre-baked resource controller actions for index, create, store,
// show, edit, update, destroy
Route::resource('rules', 'RuleController');

/**
 * ------------------------------------------
 * Settings
 * ------------------------------------------
 */
// Pre-baked resource controller actions for index, create, store,
// show, edit, update, destroy
//Route::get('settings', ['as' => 'settings.index', 'uses' => 'SettingsController@index']);
//Route::put('settings', ['as' => 'settings.update', 'uses' => 'SettingsController@update']);

