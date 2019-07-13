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

    //----------------UNAUTHENTICATED ROUTES------------------------

    //place routes that do NOT require user Authentication in this area
    Auth::routes();

    //----------------END UNAUTHENTICATED ROUTES------------------------
    Route::group(['middleware' => 'auth:web'], function()
    {//----------------AUTHENTICATED ROUTES------------------------

        //put routes here that require user authentication. In other words, if the user has to be logged in to access

        Route::get('/user', function(){
            return \Illuminate\Support\Facades\Auth::user();
        })->name('user');//this router is used to check user session status

        Route::post('/changePassword','UserAccountController@changePassword')->name('changePassword');


    });//----------------END UNAUTHENTICATED ROUTES------------------------







});//end WEB API Routes


Route::get('/{vue_capture?}', 'WebAppShellController@index')->where('vue_capture', '[\/\w\.-]*')->name('app');