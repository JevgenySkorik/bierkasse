<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\product;
use App\Models\journal;

use function Psy\debug;

class AdminController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function authenticate(Request $request): RedirectResponse
    {
        \Log::debug(json_encode($request->all()));
        $credentials = $request->validate([
            'name' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function journal()
    {
        return view('journal', [
            'journalEntries' => journal::with('product:id,name')
                ->select(['id', 'name', 'date', 'method', 'amount', 'product_id', 'total', 'notes'])
                ->orderBy('id', 'DESC')
                ->paginate(15),
            'products' => product::all(),
        ]);
    }

    public function products()
    {
        return view('products', [
            'productEntries' => product::orderBy('id', 'DESC')->paginate(15),
        ]);
    }
    
    public function debts()
    {
        $debts = [];
        $totals = [];
        $journalEntries = journal::with('product:id,name')
            ->select(['id', 'name', 'date', 'method', 'amount', 'product_id', 'total', 'notes'])
            ->where('method', 'Debt')
            ->get();
        
        foreach ($journalEntries as $entry) {
            $debts[$entry['name']][] = $entry->toArray();
        }
        foreach($debts as $name => $debtor) {
            $totals[$name] = 0;
            foreach($debtor as $debt) {
                $totals[$name] += $debt['total'];
            }
        }
        //\Log::debug(print_r($totals, true));

        return view('debts', ['debts' => $debts, 'totals' => $totals]);
    }

    public function export()
    {
        $data = [];
        $journalEntries = journal::with('product:id,name')
            ->select(['id', 'name', 'date', 'method', 'amount', 'product_id', 'total', 'notes'])
            ->get();
        $filename = date("d-m-Y_H-i-s") . '_export.csv';

        foreach ($journalEntries as $entry) {
            $data[] = [
                'id' => $entry['id'],
                'name' => $entry['name'],
                'date' => $entry['date'],
                'method' => $entry['method'],
                'amount' => $entry['amount'],
                'product' => $entry['product']['name'],
                'total' => $entry['total'],
                'notes' => $entry['notes'],
            ];
        }
        // Set headers to prompt download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // Open output stream
        $output = fopen('php://output', 'w');

        // Add CSV header
        fputcsv($output, ['id', 'name', 'date', 'method', 'amount', 'product', 'total', 'notes']);

        // Add data to CSV
        foreach ($data as $row) {
            fputcsv($output, $row);
        }

        // Close output stream
        fclose($output);
        exit;
        return back();
    }
}
