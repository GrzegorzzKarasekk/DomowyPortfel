<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SitesController extends Controller
{
    public function index()
    {
        return view('sites.index');
    }

    public function hello()
    {
        return view('sites.hello');
    }
}
