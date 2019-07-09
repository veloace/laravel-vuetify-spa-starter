<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|
| DO NOT PUT WEB API ROUTES HERE. If you are intending to call the API from
| the frontend Vue SPA, put those API routes in the webAPI group in web.php
|
| The API routes here preserve the native Laravel behaviour so you can
| still create a stateless API for third-parties or a native mobile app.
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
