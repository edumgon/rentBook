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
    return view('welcome');
});

// Authentication routes
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::get('/auth/{provider}', [App\Http\Controllers\Auth\LoginController::class, 'redirectToProvider'])->name('auth.redirect');
Route::get('/auth/{provider}/callback', [App\Http\Controllers\Auth\LoginController::class, 'handleProviderCallback'])->name('auth.callback');
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware(['auth', 'tenant'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    
    // Books
    Route::resource('books', App\Http\Controllers\BookController::class);
    Route::get('/books/search', [App\Http\Controllers\BookController::class, 'search'])->name('books.search');
    
    // Borrowers
    Route::resource('borrowers', App\Http\Controllers\BorrowerController::class);
    
    // Loans
    Route::resource('loans', App\Http\Controllers\LoanController::class);
    Route::post('/loans/{loan}/return', [App\Http\Controllers\LoanController::class, 'returnBook'])->name('loans.return');
});
