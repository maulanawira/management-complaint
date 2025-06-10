<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class KaryawanController extends Controller
{
    public function edit()
    {
        // Tampilkan form edit profil karyawan
        return view('dashboards.edit-profil-karyawan');
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user = Auth::user();
        $user->name = $request->name;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('dashboard.karyawan')->with('success', 'Profil berhasil diupdate.');
    }
    public function history()
    {
    $complaints = Complaint::with(['admin']) // Load relasi admin berdasarkan employee_id
                           ->where('user_id', auth()->id())
                           ->orderBy('created_at', 'desc')
                           ->get();
    
    return view('dashboards.karyawan-history', compact('complaints'));
    }
}