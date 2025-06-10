<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Complaint;

class AdminController extends Controller
{
    // Tampilkan dashboard admin dengan data komplain
    public function index()
    {
        $complaints = Complaint::with('user')->orderBy('created_at', 'desc')->get();
        return view('dashboards.admin', compact('complaints'));
    }

    // Tampilkan form edit profil
    public function editProfile()
    {
        return view('dashboards.edit-profil-admin');
    }

    // Proses update nama dan password
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user = auth()->user();
        $user->name = $request->name;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        // Redirect ke dashboard.admin dengan pesan sukses
        return redirect()->route('dashboard.admin')->with('success', 'Profil berhasil diupdate.');
    }

    // Generate Employee ID berdasarkan role dan department
    private function generateEmployeeId($role, $department)
    {
        // Mapping role ke huruf
        $rolePrefix = [
            'admin' => 'A',
            'karyawan' => 'K',
            'supervisor' => 'S'
        ];

        // Mapping department ke 3 huruf pertama
        $departmentMap = [
            'Produksi' => 'PRD',
            'IT' => 'ITD',
            'General Affairs' => 'GAF',
            'Human Resources' => 'HRD',
            'Keuangan' => 'KEU',
            'Sales/Marketing' => 'SAL'
        ];

        // Ambil prefix role
        $roleLetter = $rolePrefix[strtolower($role)] ?? 'X';

        // Ambil 3 huruf department
        $deptCode = $departmentMap[$department] ?? substr(strtoupper(str_replace(' ', '', $department)), 0, 3);
        if (strlen($deptCode) < 3) {
            $deptCode = str_pad($deptCode, 3, 'X');
        }

        // Hitung nomor urut berdasarkan role dan department yang sama
        $count = User::where('role', $role)
                     ->where('department', $department)
                     ->count();

        $sequence = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        return $roleLetter . '-' . $deptCode . '-' . $sequence;
    }

    // Tambah Karyawan
    public function createKaryawan(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'department' => 'nullable|string|max:100',
        ]);

        try {
            // Generate Employee ID
            $employeeId = $this->generateEmployeeId('karyawan', $request->department);

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'karyawan',
                'phone' => $request->phone,
                'address' => $request->address,
                'department' => $request->department,
                'employee_id' => $employeeId,
            ]);

            return redirect()->route('dashboard.admin')->with('success', 'Karyawan berhasil ditambahkan dengan ID: ' . $employeeId);
        } catch (\Exception $e) {
            return redirect()->route('dashboard.admin')->with('error', 'Gagal menambahkan karyawan: ' . $e->getMessage());
        }
    }

    // Tambah Supervisor
    public function createSupervisor(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:100',
        ]);

        try {
            // Generate Employee ID
            $employeeId = $this->generateEmployeeId('supervisor', $request->department);

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'supervisor',
                'employee_id' => $employeeId,
                'phone' => $request->phone,
                'department' => $request->department,
            ]);

            return redirect()->route('dashboard.admin')->with('success', 'Supervisor berhasil ditambahkan dengan ID: ' . $employeeId);
        } catch (\Exception $e) {
            return redirect()->route('dashboard.admin')->with('error', 'Gagal menambahkan supervisor: ' . $e->getMessage());
        }
    }

    // Tambah Admin
    public function createAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:100',
        ]);

        try {
            // Generate Employee ID
            $employeeId = $this->generateEmployeeId('admin', $request->department);

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'admin',
                'employee_id' => $employeeId,
                'phone' => $request->phone,
                'department' => $request->department,
            ]);

            return redirect()->route('dashboard.admin')->with('success', 'Admin berhasil ditambahkan dengan ID: ' . $employeeId);
        } catch (\Exception $e) {
            return redirect()->route('dashboard.admin')->with('error', 'Gagal menambahkan admin: ' . $e->getMessage());
        }
    }
}