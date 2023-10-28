<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/get-all-expenses', 'App\Http\Controllers\ExpenseController@getAllExpenses');
Route::get('/search-expenses/{timeline}/{purchasetype}', 'App\Http\Controllers\ExpenseController@searchExpenses');
Route::post('/add-expense', 'App\Http\Controllers\ExpenseController@addExpense');
Route::delete('/delete-expense/{id}', 'App\Http\Controllers\ExpenseController@deleteExpense');
Route::delete('/delete-all-check-ids', 'App\Http\Controllers\ExpenseController@deleteExpenses');