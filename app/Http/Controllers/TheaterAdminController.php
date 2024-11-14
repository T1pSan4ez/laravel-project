<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TheaterAdminController extends Controller
{
    public function index()
    {
        return view('admin/theater-plays');
    }
}
