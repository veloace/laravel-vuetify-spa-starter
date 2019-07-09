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


Route::prefix('webAPI')->group(function () {
    /*
    |--------------------------------------------------------------------------
    | WEB API Routes
    |
    | Our Vue application uses the Web Middleware and imports API routers from this
    | group only.
    |--------------------------------------------------------------------------
    */
    Auth::routes();
    Route::get('/user', function(){
        return \Illuminate\Support\Facades\Auth::user();
    })->name('user')->middleware('auth:web');


});//end WEB API Routes


Route::get('/{vue_capture?}', 'WebAppShellController@index')->where('vue_capture', '[\/\w\.-]*')->name('app');