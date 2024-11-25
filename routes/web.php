<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JournalController;

Route::get('/', [JournalController::class, 'index']);

Route::post('/addJournalEntry', [JournalController::class, 'addJournalEntry'])->name('addJournalEntry');
