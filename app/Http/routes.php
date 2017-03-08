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

Route::get('/', function () {
    return view('welcome');
});

Route::post('/save-user','UserController@save');
Route::post('/follow','UserController@followCeleb');

Route::get('/api/test','apiController@testTwitter');
Route::get('/api/testFacebook','apiController@testFacebook');
Route::get('/user-feeds/{id}','apiController@getUserFeeds');
Route::get('/api/save','apiController@saveFeedsToDatabase');
Route::post('/celeb','apiController@addCeleb');

Route::post('/userSuggestions','UserController@getSuggestionsOfUser');
