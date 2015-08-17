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

Route::post('/login', "UserController@checked");
Route::post('/register', "UserController@create");
Route::get('/insertuser', "UserController@insert");

Route::post('/image', 'ImageController@index');
Route::get('/image/{id}', 'ImageController@show');

Route::post('/user_profile', 'UserController@show');
Route::get('/followed/{id}', 'UserController@followed');
Route::get('/following/{id}', 'UserController@following');
Route::get('/favorite/{id}', 'UserController@favorite');

Route::post('/comment', 'CommentController@create');
Route::post('/like', 'ImageController@like');
Route::post('/upload', 'ImageController@create');
Route::post('/profile_edit', 'UserController@edit');
Route::post('/activity', 'ActivityController@create');
Route::post('/activity_attend', 'ActivityController@attend');
Route::post('/follow', 'UserController@follow');
Route::post('/activity_detail', 'ActivityController@show');
Route::get('/activity', 'ActivityController@index');

Route::get('/sales', 'SalesController@index');
Route::get('/sales/{id}', 'SalesController@show');
Route::post('/sales_comment', 'CommentController@sales_comment');
Route::post('/sales_upload', 'SalesController@create');