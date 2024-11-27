<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\journal;
use App\Models\product;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class JournalController extends Controller
{
    public function index(): View {
        return view('index', [
            'journalEntries' => journal::orderBy('id', 'DESC')->paginate(15),
            'products' => product::all(),
        ]);
    }

    public function addJournalEntry(Request $request) : RedirectResponse {
        \Log::debug(json_encode($request->all()));

        $products = $request->products;
        $amounts = $request->amounts;

        $request->validate([
            'amounts.*' => 'gt:0',
        ]);

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
