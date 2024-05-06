<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;

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
    return view('login');
});

Route::get('signup', function() {
    return view('signup');
})->name('signup');

Route::post('/login', [IndexController::class, 'login']);

Route::get('/fetch', [IndexController::class, 'fetch']);

Route::post('/insert', [IndexController::class, 'insert']);

Route::post('/update/{u_id}', [IndexController::class, 'update']);

Route::get('/getCountries', [IndexController::class, 'getCountries']);

Route::get('/getStates/{country_id}', [IndexController::class, 'getStates']);