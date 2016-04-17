<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|


Route::get('/', function () {
    return view('index');
});
*/
Route::get('/debug', function() {
  return view('debug');
});

Route::any('/index.php', function() {
  return redirect('/home');
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => 'web'], function () {
  // Scoreboard
    Route::get('/scoreboard','ScoreController@scoreview');
    Route::get('/ajax/score/board', 'ScoreController@scoreboard');
    Route::get('/ajax/score/total', 'ScoreController@totalscore');
    Route::get('/', 'HomeController@index');
    Route::get('/home', 'HomeController@index');

    // Category
    Route::get('/ajax/category','CategoryController@index');
    Route::any('/ajax/category/update','CategoryController@update');
    Route::any('/ajax/category/modify','CategoryController@modify');
    Route::any('/ajax/category/destroy','CategoryController@destroy');
    Route::any('/ajax/category/store','CategoryController@store');

    // Challenge
    Route::get('/ajax/challenge','ChallengeController@index');
    Route::get('/ajax/challenge/listall','ChallengeController@listall');
    Route::any('/ajax/challenge/data','ChallengeController@data');
    Route::any('/ajax/challenge/info','ChallengeController@info');
    Route::any('/ajax/challenge/attempt','ChallengeController@attempt');

    Route::any('/ajax/challenge/store','ChallengeController@store');
    Route::any('/ajax/challenge/modify_name','ChallengeController@modify_name');
    Route::any('/ajax/challenge/modify','ChallengeController@modify');
    Route::any('/ajax/challenge/destroy','ChallengeController@destroy');
    Route::any('/ajax/challenge/modify_hash','ChallengeController@modify_hash');

    Route::any('/ajax/challenge/file_put', 'ChallengeController@file_put');
    Route::any('/ajax/challenge/file_get/{chalid}/{filename}', ['uses' => 'ChallengeController@file_get']);
    Route::any('/ajax/challenge/file_remove/{chalid}/{filename}', ['uses' => 'ChallengeController@file_remove']);
    Route::any('/ajax/challenge/info_files', 'ChallengeController@info_files');

    Route::any('/ajax/competition/available', 'CompetitionController@available');
    Route::any('/ajax/competition/update_time', 'CompetitionController@update_time');
    Route::any('/ajax/competition/start_time', 'CompetitionController@start_time');
    Route::any('/ajax/competition/end_time', 'CompetitionController@end_time');

    // Statistics
    Route::any('/ajax/stats/challenge_info/{chalid}', ['uses' => 'StatsController@challenge_info']);



});

Route::group(['middleware' => 'web'], function() {
  Route::auth();

  Route::get('/admin', 'AdminController@index');
});

Route::group(['middleware' => ['web']], function () {
  Route::get('auth/login', 'Auth\AuthController@getLogin');
  Route::post('auth/login', 'Auth\AuthController@postLogin');
  Route::get('auth/logout', 'Auth\AuthController@getLogout');

  // Registration routes...
  Route::get('auth/register', 'Auth\AuthController@getRegister');
  Route::post('auth/register', 'Auth\AuthController@postRegister');
});
