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

$app->get('/', function() use ($app) {
    return $app->welcome();
});

$app->group(['middleware' => 'auth','prefix'=>'geo/v1','namespace' => 'App\Http\Controllers'], function () use ($app)
{
    $app->get('/district','GeoController@district');

    $app->get('/district/find','GeoController@find');
    
});
