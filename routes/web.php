<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
Route::get('/', function () {
    return view('welcome');
});
*/

Auth::routes();

Route::get('/', 'SiteController@index');

Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

Route::get('/question/{question}/index', 'QuestionController@index');
Route::post('/question/{question}/submit', 'AnswerController@store');

Route::get('/question/{question}/results', 'QuestionController@results');
