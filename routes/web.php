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
Route::get('/settings', 'SettingsController@index');

// Uwierzytelnianie
Route::get('/login', 'Auth\AuthController@getLogin');
Route::post('/login', 'Auth\AuthController@postLogin');
Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');

// Rejestracja
Route::get('/register', 'Auth\AuthController@getRegister');
Route::post('/register', 'Auth\AuthController@postRegister');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//Incomes
Route::get('/income', 'IncomeController@index');
Route::post('/income', 'IncomeController@create');

//Expenses
Route::get('/expense', 'ExpenseController@index');
Route::post('/expense', 'ExpenseController@create');
Route::post('/expense/showStatusSelectedExpense', 'ExpenseController@showStatusSelectedExpense');

//Balances
Route::get('/balances', 'BalancesController@index');
Route::get('/balances/lastMonth', 'BalancesController@lastMonth');
Route::get('/balances/thisYear', 'BalancesController@thisYear');
Route::post('/balances/unregular','BalancesController@unregular')->name('unregular');
// BALANCES Edit
Route::post('/balances/editIncome','IncomeController@editIncomeFromBalance');
Route::post('/balances/editExpense','ExpenseController@editExpenseFromBalance');

// BALANCES Delete
Route::post('/balances/deleteIncome','IncomeController@deleteIncomeFromBalance');
Route::post('/balances/deleteExpense','ExpenseController@deleteExpenseFromBalance');

//Ustawienia
//Przychody
Route::post('/settings/newIncomeCategory', 'SettingsController@createNewIncomeNameCategory');
Route::post('/settings/editIncomeCategory', 'SettingsController@changeIncomeNameCategory');
Route::post('/settings/deleteIncomeCategory', 'SettingsController@deleteIncomeNameCategory');
//Wydatki
Route::post('/settings/newExpenseCategory', 'SettingsController@createNewExpenseNameCategory');
Route::post('/settings/editExpenseCategory', 'SettingsController@changeExpenseNameCategory');
Route::post('/settings/deleteExpenseCategory', 'SettingsController@deleteExpenseNameCategory');
Route::post('/settings/showCategoryLimit', 'SettingsController@showCategoryLimit');
Route::post('/settings/deleteLimit', 'SettingsController@deleteLimitCategory');

//Sposoby Płatności
Route::post('/settings/newPaymentMethodCategory', 'SettingsController@createNewPaymentMethodNameCategory');
Route::post('/settings/editPaymentMethodCategory', 'SettingsController@changePaymentMethodNameCategory');
Route::post('/settings/deletePaymentMethodCategory', 'SettingsController@deletePaymentMethodNameCategory');
//Użytkownik
Route::post('/settings/editUser', 'SettingsController@changeTheUserData');
Route::post('/settings/deleteUser', 'SettingsController@deleteUser');