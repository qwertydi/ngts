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
//$api->get('devices', 'App\Http\Controllers\DevicesController@devicesByUser');

$api = app('Dingo\Api\Routing\Router');

//Show user info via restful service.
$api->version('v1', function ($api) {
    $api->get('/login','App\Http\Controllers\ApiController@accessToken');
    // NOT TESTED
    $api->group(['middleware' => ['web','auth:api']], function($api)
    {
        $api->get('/user', 'App\Http\Controllers\ApiController@user');
        $api->get('/devices','App\Http\Controllers\ApiController@devicesUser');
        $api->get('/devices/add','App\Http\Controllers\ApiController@deviceAdd');
        $api->post('/todo/','App\Http\Controllers\ApiController@show');
        $api->get('/todo/{todo}','App\Http\Controllers\ApiController@show');
        $api->put('/todo/{todo}','App\Http\Controllers\ApiController@update');
        $api->delete('/todo/{todo}','App\Http\Controllers\ApiController@destroy');
    });
});