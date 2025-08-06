<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\journal;
use App\Models\product;
use App\Models\name;
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
                ->paginate(10),
            'products' => product::all(),
        ]);
    }

    public function addJournalEntry(Request $request) : RedirectResponse {
        
        $clientName = explode(' (', $request->name)[0]; //Split client's balance from name

        // If new name, add to names table(for autocomplete)
        $nameExists = name::where('name', $clientName)->exists();
        if (!$nameExists) {
            $newName = new name;
            $newName->name = $clientName;
            $newName->save();
        }
        
        // Add new entry
        $products = $request->products;
        $amounts = $request->amounts;

        $request->validate([
            'amounts.*' => 'gt:0',
        ]);

        $nameEntry = name::where('name',$clientName)->first();
        $currentBalance = $nameEntry->balance;

        foreach ($products as $index => $product) {
            $productName = explode('|', $product)[0];

            $newJournalEntry = new journal;
            $newJournalEntry->name = $clientName;
            $newJournalEntry->product_id = product::where('name', $productName)->first()['id'];
            $newJournalEntry->amount = $amounts[$index];

            //Update product quantity
            $currentQuantity  = product::where('name', $productName)->first()['quantity']; // Get current quantity for this product
            $editedProduct = product::where('name', $productName)->first();

            if($amounts[$index] < $currentQuantity) {
                $editedProduct->quantity = $currentQuantity - $amounts[$index];
            }
            else {
                $editedProduct->quantity = 0;
            }
            $editedProduct->save();

            $newJournalEntry->date = $request->date;
            $newJournalEntry->method = $request->method;
            $subTotal = product::where('name', $productName)->first()['price'] * $amounts[$index];

            // Deduct payment from client's balance, if it is sufficient
            if($request->method == 'Deposit' && $currentBalance >= $subTotal) {
                $currentBalance -= $subTotal;
                $newJournalEntry->method = 'Deposit';
            }
            elseif($request->method == 'Deposit' && $currentBalance < $subTotal) {
                $newJournalEntry->method = 'Debt';
            }
            $newJournalEntry->total = $subTotal;
            $newJournalEntry->notes = $request->notes;

            $newJournalEntry->save();
        }

        $nameEntry->balance = $currentBalance;
        $nameEntry->save();
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

        if (!Product::where('name', $newProductEntry->name)->exists()) {
            $newProductEntry->save();
        }
        else {
            return back()->withErrors(['error' => __('messages.delete_prod')]);
        }

        session()->flash('success', __('messages.prod_create'));
        return redirect('products');
    }

    public function updateProductEntries(Request $request) : RedirectResponse {
        \Log::debug(json_encode($request->all()));

        foreach ($request->entries as $index => $entry) {
            $productEntry = product::find($index);
            if (isset($entry['delete'])) {

                if (!$productEntry->journal()->exists()) {
            
                    $productEntry->delete();
                }
                else {
                    return back()->withErrors(['error' => __('messages.delete_prod')]);
                }
                continue;
            }
            $productEntry->name = $entry['name'];
            $productEntry->price = $entry['price'];
            $productEntry->quantity = $entry['quantity'];
            $productEntry->save();
        }
        session()->flash('success', __('messages.prod_success'));
        return redirect('products');
    }

    public function updateJournalEntries(Request $request) : RedirectResponse {
        \Log::debug(json_encode($request->all()));

        // "entries":{"2":{"name":"com! Jevgenijs Skoriks","date":"2024-12-11","method":"Cash","product":"Salty chips","amount":"1","total":"1","notes":null},"1":{"name":"com! Jevgenijs Skoriks","date":"2024-12-11","method":"Cash","product":"Ilguciema","amount":"3","total":"4.5","notes":null}},"save":{"2":"1"}

        foreach ($request->entries as $index => $entry) {
            
            $journalEntry = journal::find($index);
            $journalEntry->name = $entry['name'];
            $journalEntry->product_id = product::where('name', $entry['product'])->first()['id'];
            $oldAmount = $journalEntry->amount;
            $journalEntry->amount = $entry['amount'];

            //Update product quantity
            $currentQuantity  = product::where('name', $entry['product'])->first()['quantity']; // Get current quantity for this product
            $editedProduct = product::where('name', $entry['product'])->first();
            if($entry['amount'] <= $currentQuantity) {
                $newAmount = isset($entry['remove']) ? 0 : $entry['amount']; // Restore product quantity for deleted entry
                $editedProduct->quantity = $currentQuantity + ($oldAmount - $newAmount);
            }
            $editedProduct->save();

            //Update clients' balance
            $clientEntry = name::where('name', $entry['name'])->first();
            $currentBalance = $clientEntry->balance;
            
            //Update balance when method is changed to deposit
            if($entry['method'] == 'Deposit' && $journalEntry->method != 'Deposit' && !isset($entry['remove'])) {
                if($currentBalance >= $entry['total']) {
                    $clientEntry->balance -= $entry['total'];
                    $journalEntry->method = 'Deposit';
                }
            }
            //Update balance when total price is changes
            else if($entry['method'] == 'Deposit' && $journalEntry->method == 'Deposit') {
                if($currentBalance >= $entry['total']) {
                    $clientEntry->balance += $journalEntry['total'] - $entry['total'];
                    $journalEntry->method = 'Deposit';
                }
                else {
                    $journalEntry->method = 'Debt';
                }
            }
            //Restore balance for deleted entries, or when method changed to cash or debt
            elseif(($entry['method'] != 'Deposit' || isset($entry['remove'])) && $journalEntry->method == 'Deposit') {
                $clientEntry->balance += $entry['total'];   
            }
            
            $clientEntry->save();

            if(!isset($entry['remove'])) {
                $journalEntry->date = $entry['date'];
                //Changing method to deposit required updating balance
                if($entry['method'] != 'Deposit') {
                    $journalEntry->method = $entry['method']; 
                }
                $journalEntry->total = $entry['total'];
                $journalEntry->notes = $entry['notes'];
                $journalEntry->save();
                
            }
            else {
                $journalEntry->delete();
            }
        }
        session()->flash('success', __('messages.balance_upd'));

        return redirect('journal');
    }

    public function updateDebts(Request $request) : RedirectResponse {
        if(!is_null($request->debts)) {
            foreach ($request->debts as $index => $debt) {
                $debtEntry = journal::find($index);
                if (isset($debt['pay'])) {
                    $debtEntry->method = 'Cash';
                    $debtEntry->save();
                }
            }
        }
        session()->flash('success', __('messages.debt_upd'));
        return redirect('debts');
    }


    public function updateBalances(Request $request) : RedirectResponse {
        \Log::debug(json_encode($request->all()));

        foreach ($request->entries as $index => $entry) {
            $nameEntry = name::find($index);
            if (isset($entry['withdraw'])) {
                $nameEntry->balance = 0;
            }
            elseif ($entry['refillWith'] > 0) {
                
                $nameEntry->balance = $nameEntry->balance + $entry['refillWith'];
                
            }
            $nameEntry->save();
        }
        session()->flash('success', __('messages.balance_upd'));

        return redirect('balances');
    }

    //Add new name, if nothing is purchased yet
    public function addName(Request $request) : RedirectResponse {
        $nameExists = name::where('name', $request->name)->exists();
        if (!$nameExists) {
            $newName = new name;
            $newName->name = $request->name;
            $newName->balance = $request->balance;
            $newName->save();
        }
        return redirect('balances');
        
    }
    
}

