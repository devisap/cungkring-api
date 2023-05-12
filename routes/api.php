<?php

use App\Http\Controllers\api\v1\AuthApi;
use App\Http\Controllers\api\v1\master\FloorApi;
use App\Http\Controllers\api\v1\master\KabupatenKotaApi;
use App\Http\Controllers\api\v1\master\KecamatanApi;
use App\Http\Controllers\api\v1\master\KelurahanApi;
use App\Http\Controllers\api\v1\master\ProvinsiApi;
use App\Http\Controllers\api\v1\master\RoofApi;
use App\Http\Controllers\api\v1\master\WallApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->group(function(){
    Route::get('tes', function(){
        return 'berhasil';
    });
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
        
        // KECAMATAN API
        Route::prefix('kecamatan')
            ->controller(KecamatanApi::class)->group(function(){
                Route::get('/', 'index');
        });
        
        // KABUPATEN KOTA API
        Route::prefix('kabkot')
            ->controller(KabupatenKotaApi::class)->group(function(){
                Route::get('/', 'index');
        });
        
        // KABUPATEN KOTA API
        Route::prefix('provinsi')
            ->controller(ProvinsiApi::class)->group(function(){
                Route::get('/', 'index');
        });

        // FLOOR API
        Route::prefix('floor')
            ->controller(FloorApi::class)->group(function(){
                Route::get('/', 'index');
        });
        
        // ROOF API
        Route::prefix('roof')
            ->controller(RoofApi::class)->group(function(){
                Route::get('/', 'index');
        });
        
        // WALL API
        Route::prefix('wall')
            ->controller(WallApi::class)->group(function(){
                Route::get('/', 'index');
        });
        
    });
});
