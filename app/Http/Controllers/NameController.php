<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\name;

class NameController extends Controller
{
    public function autocomplete(Request $request)
    {
        \Log::debug("autocomplete request");
        $query = $request->get('query');

        $names = name::where('name', 'LIKE', "%{$query}%")->pluck('name', 'balance')->map(function ($name, $balance) {
            return "$name (€$balance)";
        });
        return response()->json($names);
    }


}
