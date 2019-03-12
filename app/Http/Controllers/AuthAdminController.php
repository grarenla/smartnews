<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthAdminController extends Controller
{
    function index()
    {
        return view('index');
    }

    function loginView()
    {
        if (Auth::check()) {
            return redirect('/');
        } else {
            return view('auth/login');
        }
    }

    function login(Request $request)
    {

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'role' => 'admin'])) {
            return redirect()->intended('/');
        } else {
            return redirect('/login')->with('notification', 'Account or password is incorrect.');
        }
    }

    function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}