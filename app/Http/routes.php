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
Route::post('/save-admin','UserController@saveAdmin');
Route::post('/follow','UserController@followCeleb');
Route::post('/un-follow','UserController@unFollowCeleb');

Route::get('/api/test','apiController@testTwitter');
Route::get('/api/testFacebook','apiController@testFacebook');
Route::get('/user-feeds/{id}/{page}','UserController@getUserFeeds');
Route::get('/new-user-feeds/{id}/{postId}','UserController@getNewUserFeeds');
Route::get('/user-following/{id}','UserController@getUserFollowing');
Route::get('/api/save','apiController@saveFeedsToDatabase');
Route::post('/api/login-email','UserController@loginEmail');
Route::post('/api/login-facebook','UserController@loginFacebook');

Route::get('/testInstagram','apiController@testInstagram');

Route::get('/categories','apiController@getCategories');
Route::get('/user-suggestions/{id}','UserController@getSuggestions');
Route::get('/explore/{id}','UserController@getExploreFeeds');

//celebrities
Route::post('/add-celeb','CelebrityController@addCeleb');
Route::get('/celeb/{celebId}/{userId}','CelebrityController@getCeleb');
Route::get('/search-celebs-name/{celebName}/{userId}','CelebrityController@getCelebsByName');
Route::get('/get-celebs-category/{categId}','CelebrityController@getCelebsByCategory');
Route::get('/get-celebs-country/{country}','CelebrityController@getCelebsByCountry');
Route::get('/celeb-feeds/{celebId}','CelebrityController@getCelebFeeds');

//web
Route::get('/home/{id}/{postId}','UserWebController@getUserFeeds');
Route::get('/{id}/celebrities/all','UserWebController@getCelebrities');
Route::get('/followedCelebs/{id}', 'UserWebController@getFollowedCelebs');
Route::get('/{id}/celebrities/categories','UserWebController@getCategories');
Route::get('/{id}/celebrities/region','UserWebController@getRegions');
Route::get('/{id}/timeline/{celebid}','UserWebController@getCelebFeeds');
Route::get('/edit-account/{id}','UserWebController@getUser');
Route::get('/{id}/admin/add-celebrity','UserWebController@getAdminAddCeleb');
Route::get('/{id}/explore','UserWebController@getExploreFeeds');
Route::get('/{id}/follow/{celebid}','UserWebController@followCeleb');
Route::get('/{id}/unfollow/{celebid}','UserWebController@unFollowCeleb');
Route::get('/{id}/celebrities/category/{categoryId}','UserWebController@getCelebsByCategory');
Route::post('/{id}/search','UserWebController@getCelebsByName');
Route::get('/{id}/suggestions','UserWebController@getSuggestions');
Route::get('/{id}/about','UserWebController@getAbout');
Route::get('/{id}/contact','UserWebController@getContact');
Route::get('/following/{id}','UserWebController@getFollowedCelebs');

/////