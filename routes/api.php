<?php

use App\Http\Controllers\api\v1\AuthApi;
use App\Http\Controllers\api\v1\master\FloorApi;
use App\Http\Controllers\api\v1\master\KabupatenKotaApi;
use App\Http\Controllers\api\v1\master\KecamatanApi;
use App\Http\Controllers\api\v1\master\KelurahanApi;
use App\Http\Controllers\api\v1\master\ProvinsiApi;
use App\Http\Controllers\api\v1\master\RoofApi;
use App\Http\Controllers\api\v1\master\UserApi;
use App\Http\Controllers\api\v1\master\WallApi;
use App\Http\Controllers\api\v1\transaction\DashboardApi;
use App\Http\Controllers\api\v1\transaction\SurveyAirBersihApi;
use App\Http\Controllers\api\v1\transaction\SurveySanitasiApi;
use App\Http\Controllers\api\v1\transaction\UsulAladinApi;
use App\Http\Controllers\api\v1\transaction\UsulListrikApi;
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

// cungkring
Route::prefix('v1')->group(function () {
    Route::get('tes', function () {
        return 'berhasil';
    });
    // AUTH API
    Route::controller(AuthApi::class)->group(function () {
        Route::post('login', 'login');
    });

    // KELURAHAN API
    Route::prefix('kelurahan')
        ->controller(KelurahanApi::class)->group(function () {
            Route::get('/', 'index');
        });

    // KECAMATAN API
    Route::prefix('kecamatan')
        ->controller(KecamatanApi::class)->group(function () {
            Route::get('/', 'index');
        });

    // KABUPATEN KOTA API
    Route::prefix('kabkot')
        ->controller(KabupatenKotaApi::class)->group(function () {
            Route::get('/', 'index');
        });

    // KABUPATEN KOTA API
    Route::prefix('provinsi')
        ->controller(ProvinsiApi::class)->group(function () {
            Route::get('/', 'index');
        });

    // FLOOR API
    Route::prefix('floor')
        ->controller(FloorApi::class)->group(function () {
            Route::get('/', 'index');
        });

    // ROOF API
    Route::prefix('roof')
        ->controller(RoofApi::class)->group(function () {
            Route::get('/', 'index');
        });

    // WALL API
    Route::prefix('wall')
        ->controller(WallApi::class)->group(function () {
            Route::get('/', 'index');
        });

    // USULALADIN API
    Route::prefix('usulaladin')
        ->controller(UsulAladinApi::class)->group(function () {
            Route::get('/', 'index');
            Route::get('latlong', 'getLatLong');
            Route::post('/', 'store');
        });

    // USULLISTRIK API
    Route::prefix('usullistrik')
        ->controller(UsulListrikApi::class)->group(function () {
            Route::get('/', 'index');
            Route::get('latlong', 'getLatLong');
            Route::post('/', 'store');
        });
    
    // SURVEY AIR BERSIH
    Route::prefix('surveyairbersih')
    ->controller(SurveyAirBersihApi::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
    });
    
    // SURVEY SANITASI
    Route::prefix('surveysanitasi')
        ->controller(SurveySanitasiApi::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/', 'store');
        });

    // DASHBOARD API
    Route::prefix('dashboard')
        ->controller(DashboardApi::class)->group(function () {
            Route::get('usulaladin', 'getUsulAladin');
            Route::get('usullistrik', 'getUsulListrik');
        });

    // PROFILE API
    Route::prefix('user')
        ->controller(UserApi::class)->group(function () {
            Route::get('/', 'index');
        });
});
