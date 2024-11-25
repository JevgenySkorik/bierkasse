<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\journal;

class JournalController extends Controller
{
    public function index() {
        return view('index', ['journalEntries' => journal::orderBy('id', 'DESC')->get()]);
    }

    public function addJournalEntry(Request $request) {
        \Log::debug(json_encode($request->all()));

        $newJournalEntry = new journal;
        $newJournalEntry->name = $request->name;
        $newJournalEntry->product = $request->product;
        $newJournalEntry->amount = $request->amount;
        $newJournalEntry->date = $request->date;
        $newJournalEntry->method = $request->method;
        $newJournalEntry->total = $request->total;
        $newJournalEntry->notes = $request->notes;
        $newJournalEntry->save();

        return redirect('/');
    }
}
