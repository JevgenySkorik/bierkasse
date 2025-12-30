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

        $names = name::where('name', 'LIKE', "%{$query}%")
            ->get(['name', 'balance'])
            ->map(function ($item) {
                $fmt = number_format($item->balance, 1);
                return "{$item->name} (€$fmt)";
            });
        return response()->json($names);
    }


}
