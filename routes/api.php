<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/


$api = app('Dingo\Api\Routing\Router');

//Show user info via restful service.
$api->version('v1', function ($api) {
    $api->get('devices', 'App\Http\Controllers\DevicesController@devicesByUser');
});
    
