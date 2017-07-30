<?php
/**
 * SSHAM - SSH Access Manager Web Interface.
 *
 * Copyright (c) 2017 by Paco Orozco <paco@pacoorozco.info>
 *
 * This file is part of some open source application.
 *
 * Licensed under GNU General Public License 3.0.
 * Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2017 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 * @link        https://github.com/pacoorozco/ssham
 */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);
/*  ------------------------------------------
 *  Authentication
 *  ------------------------------------------
 */
// Authentication Routes...
Route::get('auth/login', ['as' => 'login', 'uses' => 'Auth\LoginController@showLoginForm']);
Route::post('auth/login', ['as' => 'login', 'uses' => 'Auth\AuthController@postLogin']);
Route::get('auth/logout', ['as' => 'logout', 'uses' => 'Auth\AuthController@getLogout']);

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');


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
// Datatables Ajax route.
Route::get('users/data', ['as' => 'users.data', 'uses' => 'UserController@data']);
// Delete confirmation route - uses the show/details view.
Route::get('users/{users}/delete', ['as' => 'users.delete', 'uses' => 'UserController@delete']);
// Pre-baked resource controller actions for index, create, store, 
// show, edit, update, destroy
Route::resource('users', 'UserController');

/**
 * ------------------------------------------
 * Hosts
 * ------------------------------------------
 */
// Datatables Ajax route.
Route::get('hosts/data', ['as' => 'hosts.data', 'uses' => 'HostController@data']);
// Delete confirmation route - uses the show/details view.
Route::get('hosts/{hosts}/delete', ['as' => 'hosts.delete', 'uses' => 'HostController@delete']);
// Pre-baked resource controller actions for index, create, store,
// show, edit, update, destroy
Route::resource('hosts', 'HostController');

/**
 * ------------------------------------------
 * Usergroups
 * ------------------------------------------
 */
// Datatables Ajax route.
Route::get('usergroups/data', ['as' => 'usergroups.data', 'uses' => 'UsergroupController@data']);
// Delete confirmation route - uses the show/details view.
Route::get('usergroups/{usergroups}/delete', ['as' => 'usergroups.delete', 'uses' => 'UsergroupController@delete']);
// Pre-baked resource controller actions for index, create, store,
// show, edit, update, destroy
Route::resource('usergroups', 'UsergroupController');

/**
 * ------------------------------------------
 * Hostgroups
 * ------------------------------------------
 */
// Datatables Ajax route.
Route::get('hostgroups/data', ['as' => 'hostgroups.data', 'uses' => 'HostgroupController@data']);
// Delete confirmation route - uses the show/details view.
Route::get('hostgroups/{hostgroups}/delete', ['as' => 'hostgroups.delete', 'uses' => 'HostgroupController@delete']);
// Pre-baked resource controller actions for index, create, store,
// show, edit, update, destroy
Route::resource('hostgroups', 'HostgroupController');

/**
 * ------------------------------------------
 * Rules
 * ------------------------------------------
 */
// Datatables Ajax route.
Route::get('rules/data', ['as' => 'rules.data', 'uses' => 'RuleController@data']);
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
Route::get('settings', ['as' => 'settings.index', 'uses' => 'SettingsController@index']);
Route::put('settings', ['as' => 'settings.update', 'uses' => 'SettingsController@update']);

