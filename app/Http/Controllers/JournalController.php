<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\journal;
use App\Models\product;
use App\Models\name;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cookie;

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

    public function mobile(Request $request, $param = null): View {
        $preselectedProds = [];
        if($param != null) {
            $productIDs = explode('+', $param);
            foreach($productIDs as $prodID) {
                $productEntry = product::where('id', $prodID)->first();
                $preSelected = [
                    "name" => $productEntry->name,
                    "price" => $productEntry->price,
                    "quantity" => $productEntry->quantity
                ];
                $preselectedProds[] = $preSelected;
            }
        }
        $balance = 0;
        $debt = 0;
        if(Cookie::has('clientName')){
            $client = name::where('name', Cookie::get('clientName'))->first();
            if($client !== null) {
                $balance = name::where('name', Cookie::get('clientName'))->first()->balance;
                $debt = 0;
                $journalEntries = journal::where('method','Debt')->where('name', $client->name)->get();
                foreach($journalEntries as $journalEntry) {
                    $debt += $journalEntry->total;
                }
            }
        }
        else {
            $debt = "-";
            $balance = "-";
        }
        return view('mobile', [
            'products' => product::all(),
            'preselectedProds' => $preselectedProds,
            'clientName' => $request->cookie('clientName', ''),
            'balance' => "€ " . $balance,
            'debt' => "€ " . $debt
        ]);
    }

    public function addJournalEntry(Request $request) : RedirectResponse {
        
        $clientName = explode(' (', $request->name)[0]; //Split client's balance from name
        if(isset($request['isMobile'])) {
            Cookie::queue('clientName', $clientName, 60*9999);
        }
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
        if (isset($request['isMobile'])) {
            return redirect('/mobile');
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
            if(isset($entry['remove'])) {
                $journalEntry->delete();
            }
            else {
                $journalEntry = journal::find($index);
                $journalEntry->name = $entry['name'];
                $journalEntry->product_id = product::where('name', $entry['product'])->first()['id'];
                $journalEntry->method = $entry['method'];
                $journalEntry->amount = $entry['amount'];
                $journalEntry->date = $entry['date'];
                $journalEntry->total = $entry['total'];
            }
        }
        session()->flash('success', __('messages.journal_upd'));

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

