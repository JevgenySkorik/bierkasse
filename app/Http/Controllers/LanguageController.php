<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function changeLocale(Request $request) {
        $language = $request->input('language');
        session(['language' => $language]);
        session()->save();
        return redirect()->back();
    }
}
