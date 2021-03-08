<?php

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
Route::group(['prefix' => 'v1'], function () {
  Route::post('login', 'AuthController@login');
  Route::post('verifyUserHasPermission', 'AuthController@verifyUserHasPermission')->middleware('jwt');
  Route::get('logout', 'AuthController@logout');
  Route::get('getToken', 'AuthController@getToken')->middleware('jwt');
  Route::group(['middleware' => ['jwt']], function () {
    Route::resource('users', 'UserController');
    Route::resource('permissions', 'PermissionController');
    Route::resource('modules', 'ModuleController');
    Route::resource('departments', 'DepartmentController');
    Route::resource('orders', 'Orders\OrderController');
    Route::resource('dashboards', 'Dashboards\DashboardController');
    Route::group(['prefix' => 'processes'], function () {
      Route::get('countItemsByProcess/{process_id}/{alias}', 'Processes\ProcessController@itemsCountByProcess');
      Route::get('itemsByOrder/{order_id}/{process_id}', 'Processes\ProcessController@itemsByOrder');
      Route::put('sentItemToNextProcess/{item_id}', 'Processes\ProcessController@sentItemToNextProcess');
    });

    Route::group(['prefix' => 'business_intelligence'], function () {
      Route::get('dashboardsByDep/{department_id}', 'BI\BIController@dashboardsByDep');
      Route::get('getBIData/{dashboard_id}/{data_type}', 'BI\BIController@getBIData');
      Route::post('createVueFileComponent', 'BI\BIController@createVueFileComponent');
    });
  });
  Route::get('csrf', function () {
    return csrf_token();
  });
});

Route::get('/{any}', 'AppController@index')->where('any', '.*');
