<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\AccountCategory;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/categories', function(Request $request) {
    $type = $request->query('type');
    
    if ($type) {
        $categories = AccountCategory::where('type', $type)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    } else {
        $categories = AccountCategory::where('is_active', true)
            ->orderBy('type')
            ->orderBy('name')
            ->get();
    }
    
    return response()->json($categories);
});
