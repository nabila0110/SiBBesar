<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class AuditLogController extends BaseController
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
