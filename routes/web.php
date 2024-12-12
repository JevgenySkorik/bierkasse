<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\AdminController;

Route::get('/', [JournalController::class, 'index'])->name('index');
Route::get('/login', [AdminController::class, 'login'])->name('login');
Route::get('/logout', [AdminController::class, 'logout'])->name('logout');
Route::get('/dashboard', [AdminController::class, 'dashboard'])->middleware('auth')->name('dashboard');
Route::get('/products', [AdminController::class, 'products'])->middleware('auth')->name('products');

Route::post('/addJournalEntry', [JournalController::class, 'addJournalEntry'])->name('addJournalEntry');
Route::post('/addProductEntry', [JournalController::class, 'addProductEntry'])->name('addProductEntry');
Route::post('/updateJournalEntries', [JournalController::class, 'updateJournalEntries'])->name('updateJournalEntries');
Route::post('/updateProductEntries', [JournalController::class, 'updateProductEntries'])->name('updateProductEntries');
Route::post('/login', [AdminController::class, 'authenticate'])->name('authenticate');
