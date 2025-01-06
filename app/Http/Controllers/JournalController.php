<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\journal;
use App\Models\product;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class JournalController extends Controller
{
    public function index(): View {
        return view('index', [
            'journalEntries' => journal::with('product:id,name')
                ->select(['id', 'name', 'date', 'method', 'amount', 'product_id', 'total', 'notes'])
                ->orderBy('id', 'DESC')
                ->paginate(15),
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
            $newJournalEntry->product_id = product::where('name', $productName)->first()['id'];
            $newJournalEntry->amount = $amounts[$index];
            $newJournalEntry->date = $request->date;
            $newJournalEntry->method = $request->method;
            $newJournalEntry->total = product::where('name', $productName)->first()['price'] * $amounts[$index];
            $newJournalEntry->notes = $request->notes;
            $newJournalEntry->save();
        }

        return redirect('/');
    }

    public function addProductEntry(Request $request) : RedirectResponse {
        \Log::debug(json_encode($request->all()));

        $request->validate([
            'price' => 'gt:0',
        ]);

        $newProductEntry = new product;
        $newProductEntry->name = $request->name;
        $newProductEntry->price = $request->price;
        try {
            $newProductEntry->save();
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) { // 23000 is the SQLSTATE code for integrity constraint violation
                return back()->withErrors(['error' => 'Cannot create product, identical product already exists.']);
            }
        }
        session()->flash('success', 'Product created successfully!');
        return redirect('products');
    }

    public function updateProductEntries(Request $request) : RedirectResponse {
        \Log::debug(json_encode($request->all()));

        foreach ($request->entries as $index => $entry) {
            $productEntry = product::find($index);
            if (isset($entry['delete'])) {
                try {
                    $productEntry->delete();
                } catch (QueryException $e) {
                    if ($e->getCode() == 23000) { // 23000 is the SQLSTATE code for integrity constraint violation
                        return back()->withErrors(['error' => 'Cannot delete product. It is associated with existing journal entries.']);
                    }
                }
                continue;
            }
            $productEntry->name = $entry['name'];
            $productEntry->price = $entry['price'];
            $productEntry->save();
        }
        session()->flash('success', 'Products updated successfully!');
        return redirect('products');
    }

    public function updateJournalEntries(Request $request) : RedirectResponse {
        \Log::debug(json_encode($request->all()));

        // "entries":{"2":{"name":"com! Jevgenijs Skoriks","date":"2024-12-11","method":"Cash","product":"Salty chips","amount":"1","total":"1","notes":null},"1":{"name":"com! Jevgenijs Skoriks","date":"2024-12-11","method":"Cash","product":"Ilguciema","amount":"3","total":"4.5","notes":null}},"save":{"2":"1"}

        foreach ($request->entries as $index => $entry) {
            $journalEntry = journal::find($index);
            $journalEntry->name = $entry['name'];
            $journalEntry->product_id = product::where('name', $entry['product'])->first()['id'];
            $journalEntry->amount = $entry['amount'];
            $journalEntry->date = $entry['date'];
            $journalEntry->method = $entry['method'];
            $journalEntry->total = $entry['total'];
            $journalEntry->notes = $entry['notes'];
            $journalEntry->save();
        }
        session()->flash('success', 'Journal entries updated successfully!');
        return redirect('journal');
    }

    public function updateDebts(Request $request) : RedirectResponse {
        foreach ($request->debts as $index => $debt) {
            $debtEntry = journal::find($index);
            if (isset($debt['pay'])) {
                $debtEntry->method = 'Cash';
                $debtEntry->save();
            }
        }
        session()->flash('success', 'Debts updated successfully!');
        return redirect('debts');
    }
}
