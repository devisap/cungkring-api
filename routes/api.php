<?php

use App\Http\Controllers\api\v1\AuthApi;
use App\Http\Controllers\api\v1\master\KelurahanApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function(){
    // AUTH API
    Route::controller(AuthApi::class)->group(function(){
        Route::post('login', 'login');
    });

    Route::middleware(['checkAuthApi'])->group(function(){
        // KELURAHAN API
        Route::prefix('kelurahan')
            ->controller(KelurahanApi::class)->group(function(){
                Route::get('/', 'index');
        });
        
    });
});
