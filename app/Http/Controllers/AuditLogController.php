<?php

namespace App\Http\Controllers;

class AuditLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('audit_logs.index');
    }
}
