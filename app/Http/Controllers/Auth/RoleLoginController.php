<?php

// app/Http/Controllers/Auth/RoleLoginController.php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleLoginController extends Controller
{
    public function admin() {
        return view('auth.login-admin');
    }

    public function karyawan() {
        return view('auth.login-karyawan');
    }

    public function supervisor() {
        return view('auth.login-supervisor');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role === $request->role) {
                return redirect()->route("dashboard.{$user->role}");
            } else {
                Auth::logout();
                return back()->withErrors(['email' => 'Role tidak sesuai.']);
            }
        }

        return back()->withErrors(['email' => 'Login gagal, periksa kembali email dan password.']);
    }
}