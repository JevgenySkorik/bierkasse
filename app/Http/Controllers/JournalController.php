<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\journal;
use App\Models\product;

class JournalController extends Controller
{
    public function index() {
        return view('index', ['journalEntries' => journal::orderBy('id', 'DESC')->get(), 'products' => product::all()]);
    }

    public function addJournalEntry(Request $request) {
        \Log::debug(json_encode($request->all()));

        //"products":["lielvardes|1.5","cheeseballs|1","lielvardes|1.5"],"amounts":["1","2","3"]
        $products = $request->products;
        $amounts = $request->amounts;

        foreach ($products as $index => $product) {
            $productName = explode('|', $product)[0];

            $newJournalEntry = new journal;
            $newJournalEntry->name = $request->name;
            $newJournalEntry->product = $productName;
            $newJournalEntry->amount = $amounts[$index];
            $newJournalEntry->date = $request->date;
            $newJournalEntry->method = $request->method;
            $newJournalEntry->total = product::where('name', $productName)->first()['price'] * $amounts[$index];
            $newJournalEntry->notes = $request->notes;
            $newJournalEntry->save();
        }

        return redirect('/');
    }
}
