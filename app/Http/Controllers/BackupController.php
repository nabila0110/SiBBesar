<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BackupController extends Controller
{
    /**
     * Show backup database page.
     */
    public function index()
    {
        return view('backup-database');
    }

    /**
     * Trigger a backup action (placeholder).
     */
    public function create(Request $request)
    {
        // Placeholder: implement actual backup logic here (dump, storage, etc.).
        // For now just redirect back with a success message.
        return redirect()->route('backup-database')->with('status', 'Backup job queued (placeholder).');
    }
}
