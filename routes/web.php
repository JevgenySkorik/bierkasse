<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\NameController;

Route::get('/', [JournalController::class, 'index'])->name('index');
Route::get('/login', [AdminController::class, 'login'])->name('login');
Route::get('/logout', [AdminController::class, 'logout'])->name('logout');
Route::get('/autocomplete', [NameController::class, 'autocomplete'])->name('autocomplete');
Route::get('/journal', [AdminController::class, 'journal'])->middleware('auth')->name('journal');
Route::get('/products', [AdminController::class, 'products'])->middleware('auth')->name('products');
Route::get('/debts', [AdminController::class, 'debts'])->middleware('auth')->name('debts');
Route::get('/export', [AdminController::class, 'export'])->middleware('auth')->name('export');

Route::post('/addJournalEntry', [JournalController::class, 'addJournalEntry'])->name('addJournalEntry');
Route::post('/addProductEntry', [JournalController::class, 'addProductEntry'])->name('addProductEntry');
Route::post('/updateJournalEntries', [JournalController::class, 'updateJournalEntries'])->name('updateJournalEntries');
Route::post('/updateProductEntries', [JournalController::class, 'updateProductEntries'])->name('updateProductEntries');
Route::post('/updateDebts', [JournalController::class, 'updateDebts'])->name('updateDebts');
Route::post('/login', [AdminController::class, 'authenticate'])->name('authenticate');
