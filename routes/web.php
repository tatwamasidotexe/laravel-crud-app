<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\DashboardController;

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

Route::get('login', function() {
    return view('login');
})->name('login');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard/fetch', [DashboardController::class, 'fetchData'])->name('dashboard.fetch');
Route::get('/dashboard/logout', [DashboardController::class, 'logOut'])->name('dashboard.logout');

Route::post('/login', [IndexController::class, 'login']);

Route::get('/fetch', [IndexController::class, 'fetch']);

Route::post('/insert', [IndexController::class, 'insert']);

Route::post('/update/{u_id}', [IndexController::class, 'update']);

Route::get('/getCountries', [IndexController::class, 'getCountries']);

Route::get('/getStates/{country_id}', [IndexController::class, 'getStates']);

Route::delete('/delete/{u_id}', [IndexController::class, 'delete']);

Route::get('/download/{u_id}', [IndexController::class, 'download']);