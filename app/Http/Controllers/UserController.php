<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', '!=', 1)->orderBy('name', 'asc')->paginate(10);
        return view('admin.users', compact('users'));
    }
}
