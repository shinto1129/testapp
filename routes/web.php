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
    return view('auth/login');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/edit', [App\Http\Controllers\HomeController::class, 'edit'])->name('edit');
Route::get('/calendar', [App\Http\Controllers\HomeController::class, 'calendar'])->name('calendar');

Auth::routes();

Route::get('/management', [App\Http\Controllers\HomeController::class, 'management'])->name('management');


Route::post('/registerPeriod', [App\Http\Controllers\RegisterController::class, 'registerPeriod'])->name('registerPeriod');
Route::get('/select', [App\Http\Controllers\HomeController::class, 'select'])->name('select');
Route::post('/select', [App\Http\Controllers\HomeController::class, 'select'])->name('select');
Route::get('/chenge/{id}', [App\Http\Controllers\HomeController::class, 'chenge'])->name('chenge');
Route::get('/cale/{id}', [App\Http\Controllers\HomeController::class, 'cale'])->name('cale');
Route::get('/delete/{id}', [App\Http\Controllers\RegisterController::class, 'delete'])->name('delete');
Route::get('/sort/{id1}/{id2}/{id3}', [App\Http\Controllers\HomeController::class, 'sort'])->name('sort');
Route::get('/check/{id1}/{id2}', [App\Http\Controllers\HomeController::class, 'check'])->name('check');

Route::get('/test-redirect', function () {
    // redirect関数にパスを指定する方法
    return redirect('/');
});
