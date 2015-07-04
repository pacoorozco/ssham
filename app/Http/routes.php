<?php

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

Route::controller('auth', 'Auth\AuthController', [
    'getLogin' => 'login',
    'getLogout' => 'logout',
]);

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
Route::model('users', 'SSHAM\User');
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
Route::model('hosts', 'SSHAM\Host');
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
Route::model('usergroups', 'SSHAM\Usergroup');
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
Route::model('hostgroups', 'SSHAM\Hostgroup');
// Datatables Ajax route.
Route::get('hostgroups/data', ['as' => 'hostgroups.data', 'uses' => 'HostgroupController@data']);
// Delete confirmation route - uses the show/details view.
Route::get('hostgroups/{hostgroups}/delete', ['as' => 'hostgroups.delete', 'uses' => 'HostgroupController@delete']);
// Pre-baked resource controller actions for index, create, store,
// show, edit, update, destroy
Route::resource('hostgroups', 'HostgroupController');

/**
 * ------------------------------------------
 * HostgroupPermissions
 * ------------------------------------------
 */
Route::model('rules', 'SSHAM\Rule');
// Datatables Ajax route.
Route::get('rules/data', ['as' => 'rules.data', 'uses' => 'RuleController@data']);
// Delete confirmation route - uses the show/details view.
// Route::get('hostgroups/{hostgroups}/delete', ['as' => 'hostgroups.delete', 'uses' => 'HostgroupController@delete']);
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

