<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class CompanyController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('companies.index');
    }

    public function edit($id)
    {
        return view('companies.edit');
    }
}
