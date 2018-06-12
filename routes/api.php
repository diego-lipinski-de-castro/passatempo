<?php

// use Illuminate\Http\Request;

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


// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/home-orders', 'Api\HomeController@index');

Route::get('/snapshots', 'Api\SnapshotsController@getSnapshots');
Route::post('/snapshot/create', 'Api\SnapshotsController@createSnapshot');

Route::get('/metrics', 'Api\MetricsController@getMetrics');
Route::post('/metric/create/', 'Api\MetricsController@createMetric');

Route::get('/classifications', 'Api\ClassificationsController@getClassifications');
Route::post('/classification/create', 'Api\ClassificationsController@createClassification');

Route::get('/results', 'Api\ResultsController@getResults');
Route::get('/results/{id}', 'Api\ResultsController@getResult');
Route::post('/result/create', 'Api\ResultsController@compare');

Route::post('/action', 'Api\ActionController@doSomething');
