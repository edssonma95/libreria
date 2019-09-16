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

Route::get('/index', function () {
    return view('index');
});

//Login
Route::get('login','LoginController@loginView');
Route::post('login','LoginController@login');
Route::get('logout','LoginController@logout');
//Register
Route::post('register','RegisterController@register');
//Admin
Route::get('admin','AdminController@index')->middleware('api.auth');
//Books
Route::post('saveBook','LibrosController@add');
Route::get('loadBooks','LibrosController@getList');
Route::delete('deleteBook/{id}','LibrosController@deleteBook');
Route::get("textFilter/{query}",'LibrosController@searchBook');
//Authors
Route::get('getAuthors','AutoresController@getAuthors');
//Editorials
Route::get('getEditorials','EditorialesController@getEditorials');
