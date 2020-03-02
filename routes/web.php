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
Route::get('/', 'SitesController@index');
Route::get('/hello', 'SitesController@hello');
// Uwierzytelnianie
Route::get('/login', 'Auth\AuthController@getLogin');
Route::post('/login', 'Auth\AuthController@postLogin');
// Route::get('/logout', 'Auth\AuthController@getLogout');
Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');

// Rejestracja
Route::get('/register', 'Auth\AuthController@getRegister');
Route::post('/register', 'Auth\AuthController@postRegister');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
//Route::get('/hello', 'HomeController@hello')->name('home');

//Incomes
Route::get('/income', 'IncomeController@index');
Route::post('/income', 'IncomeController@create');
