<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

// rotte che richiedono autenticazione
Route::middleware("auth")
->namespace("Admin") //nome della cartella dove si trova il controller
->name("admin.") // aggiunge questo prefisso prima del nome della rotta
->prefix("admin")// aggiunge questo prefisso all'uri
->group(function(){
Route::get('/', 'HomeController@index')-> name('index');
Route::resource("posts", "PostController");
});
