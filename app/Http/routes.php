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
Route::get('/user-feeds/{id}','UserController@getUserFeeds');
Route::get('/api/save','apiController@saveFeedsToDatabase');

Route::get('/testInstagram','apiController@testInstagram');

Route::get('/categories','apiController@getCategories');
Route::post('/userSuggestions','UserController@getSuggestionsOfUser');

//celebrities
Route::post('/add-celeb','CelebrityController@addCeleb');
Route::get('/get-celebs-name/{celebName}','CelebrityController@getCelebsByName');
Route::get('/get-celebs-category/{categId}','CelebrityController@getCelebsByCategory');
Route::get('/get-celebs-country/{country}','CelebrityController@getCelebsByCountry');