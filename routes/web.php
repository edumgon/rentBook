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

// Test login for development (only works in local environment)
Route::get('/test-login', [App\Http\Controllers\Auth\TestLoginController::class, 'testLogin']);

// Protected routes
Route::middleware(['auth', 'tenant'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    
    // Tenant testing (local only)
    Route::get('/tenant-test', [App\Http\Controllers\TenantTestController::class, 'index'])->name('tenant.test');
    Route::post('/tenant-test/create', [App\Http\Controllers\TenantTestController::class, 'createSampleData'])->name('tenant.test.create');
    
    // Books
    Route::get('/books/search', [App\Http\Controllers\BookController::class, 'search'])->name('books.search');
    Route::get('/books/search-by-isbn', [App\Http\Controllers\BookController::class, 'searchByIsbn'])->name('books.search-by-isbn');
    Route::get('/books/details', [App\Http\Controllers\BookController::class, 'getDetails'])->name('books.details');
    Route::post('/books/import', [App\Http\Controllers\BookController::class, 'importFromApi'])->name('books.import');
    Route::resource('books', App\Http\Controllers\BookController::class);
    
    // Borrowers
    Route::get('/borrowers/search', [App\Http\Controllers\BorrowerController::class, 'search'])->name('borrowers.search');
    Route::get('/borrowers/statistics', [App\Http\Controllers\BorrowerController::class, 'statistics'])->name('borrowers.statistics');
    Route::resource('borrowers', App\Http\Controllers\BorrowerController::class);
    
    // Loans
    Route::resource('loans', App\Http\Controllers\LoanController::class);
    Route::post('/loans/{loan}/return', [App\Http\Controllers\LoanController::class, 'returnBook'])->name('loans.return');
});
