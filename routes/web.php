<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\AdminController;

Route::get('/', [JournalController::class, 'index'])->name('index');
Route::get('/login', [AdminController::class, 'login'])->name('login');
Route::get('/logout', [AdminController::class, 'logout'])->name('logout');
Route::get('/dashboard', [AdminController::class, 'dashboard'])->middleware('auth')->name('dashboard');

Route::post('/addJournalEntry', [JournalController::class, 'addJournalEntry'])->name('addJournalEntry');
Route::post('/updateJournalEntries', [JournalController::class, 'updateJournalEntries'])->name('updateJournalEntries');
Route::post('/login', [AdminController::class, 'authenticate'])->name('authenticate');
