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

Route::group(['middleware' => ['web']],function(){

    Route::get('/','UserWebController@getWelcomePage');
    Route::post('/register','UserWebController@save');
    Route::post('/login','UserWebController@loginEmail');
    Route::get('/home','UserWebController@getUserFeeds');
    Route::get('/logout','UserWebController@logOut');
    Route::post('/add-celeb','UserWebController@addCeleb');
    Route::post('/add-admin','UserWebController@saveAdmin');
    Route::post('/search','UserWebController@getCelebsByName');
    Route::get('/celebrities/all','UserWebController@getCelebrities');
    Route::get('/followedCelebs', 'UserWebController@getFollowedCelebs');
    Route::get('/celebrities/categories','UserWebController@getCategories');
    Route::get('/timeline/{celebName}','UserWebController@getCelebFeeds');
    Route::get('/edit-account','UserWebController@getUser');
    Route::get('/admin/add-celebrity','UserWebController@getAdminAddCeleb');
    Route::get('/explore','UserWebController@getExploreFeeds');
    Route::get('/follow/{celebid}','UserWebController@followCeleb');
    Route::get('/unfollow/{celebid}','UserWebController@unFollowCeleb');
    Route::get('/celebrities/category/{categoryId}','UserWebController@getCelebsByCategory');
    Route::get('/suggestions','UserWebController@getSuggestions');
//    Route::get('/about','UserWebController@getAbout');
    Route::get('/contact','UserWebController@getContact');
    Route::get('/following','UserWebController@getFollowedCelebs');
    Route::get('/admin/add-admin','UserWebController@getAddAdmin');

});

/////