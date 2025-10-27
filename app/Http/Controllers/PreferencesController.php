<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PreferencesController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        return view('preferences.index', compact('user'));
    }
}
