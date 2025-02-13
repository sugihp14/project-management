<?php

use App\Http\Controllers\DashboardController;
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

Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');
Route::middleware(['web'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('dashboard'); // Redirect ke dashboard saat akses "/"
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
