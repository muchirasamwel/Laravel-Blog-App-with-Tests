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


Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/', 'HomeController@index')->name('home');
Route::resource('blog', 'BlogController');
Route::get('/delete/{id}', 'BlogController@destroy');
Route::get('/today', 'BlogController@todaysBlog');
Route::post('/search', 'BlogController@searchBlog');
Route::get('/search', 'HomeController@index');

