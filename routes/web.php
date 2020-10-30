<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great! 
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('login', 'user\UserController@login')->name('Login');
Route::post('loginUser', 'user\UserController@loginUser');
Route::get('tickets', 'admin\AdminController@alltickets')->name('Tickets');
Route::get('createTicket', 'admin\AdminController@newTicket')->name('Create');
Route::post('/saveTicket', 'admin\AdminController@saveTicket');
Route::get('logout', 'admin\AdminController@logout');