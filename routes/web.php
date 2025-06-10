<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\Auth\RoleLoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\SupervisorController;
use App\Models\Complaint;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// Halaman login awal
Route::get('/login', function () {
    return view('auth.role-select');
})->name('login')->middleware('guest');

// Form login per role
Route::get('/login/admin', [RoleLoginController::class, 'admin'])->name('login.admin')->middleware('guest');
Route::get('/login/karyawan', [RoleLoginController::class, 'karyawan'])->name('login.karyawan')->middleware('guest');
Route::get('/login/supervisor', [RoleLoginController::class, 'supervisor'])->name('login.supervisor')->middleware('guest');

// Proses login
Route::post('/login/custom', [RoleLoginController::class, 'login'])->name('login.custom')->middleware('guest');

// Redirect root ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Route setelah login
Route::middleware(['auth'])->group(function () {

    // Redirect dashboard sesuai role
    Route::get('/dashboard', function () {
        $user = Auth::user();

        return match ($user->role) {
            'admin' => redirect()->route('dashboard.admin'),
            'supervisor' => redirect()->route('dashboard.supervisor'),
            'karyawan' => redirect()->route('dashboard.karyawan'),
            default => abort(403),
        };
    })->name('dashboard');

    // Untuk ADMIN
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/dashboard/admin', [ComplaintController::class, 'adminIndex'])->name('dashboard.admin');

        // Profil admin
        Route::get('/edit-profil-admin', [AdminController::class, 'editProfile'])->name('admin.editProfile');
        Route::put('/update-profil-admin', [AdminController::class, 'updateProfile'])->name('admin.updateProfile');

        // Update status & feedback
        Route::patch('/complaints/{complaint}/update-status', [ComplaintController::class, 'updateStatus'])->name('complaints.updateStatus');
        Route::patch('/complaints/{complaint}/feedback', [ComplaintController::class, 'updateFeedback'])->name('complaints.feedback');

        // Tambah user
        Route::post('/admin/create-karyawan', [AdminController::class, 'createKaryawan'])->name('admin.createKaryawan');
        Route::post('/admin/create-supervisor', [AdminController::class, 'createSupervisor'])->name('admin.createSupervisor');
        Route::post('/admin/create-admin', [AdminController::class, 'createAdmin'])->name('admin.createAdmin');
    });

    // Untuk SUPERVISOR
    Route::middleware(['role:supervisor'])->group(function () {
        Route::get('/dashboard/supervisor', [SupervisorController::class, 'dashboard'])->name('dashboard.supervisor');

        // Profil supervisor
        Route::get('/edit-profile-supervisor', [SupervisorController::class, 'editProfile'])->name('supervisor.editProfile');
        Route::put('/update-profile-supervisor', [SupervisorController::class, 'updateProfile'])->name('supervisor.updateProfile');

        // Export laporan
        Route::get('/dashboards/export/summary', [SupervisorController::class, 'exportSummary'])->name('dashboards.export.summary');
        Route::get('/dashboards/export/filtered', [SupervisorController::class, 'exportFiltered'])->name('dashboards.export.filtered');
    });

    // Untuk KARYAWAN
    Route::middleware(['role:karyawan'])->group(function () {
        Route::get('/dashboard/karyawan', function () {
            $complaints = Complaint::where('user_id', auth()->id())->latest()->get();
            return view('dashboards.karyawan', compact('complaints'));
        })->name('dashboard.karyawan');

        Route::get('/dashboard/karyawan-history', function () {
            $complaints = Complaint::where('user_id', auth()->id())->latest()->get();
            return view('dashboards.karyawan-history', compact('complaints'));
        })->name('dashboard.karyawan.history');

        // Komplain
        Route::post('/complaints', [ComplaintController::class, 'store'])->name('complaints.store');
        Route::delete('/complaints/{id}', [ComplaintController::class, 'destroy'])->name('complaints.destroy');

        // Profil karyawan
        Route::get('/edit-profil-karyawan', [KaryawanController::class, 'edit'])->name('karyawan.editProfile');
        Route::put('/update-profil-karyawan', [KaryawanController::class, 'update'])->name('karyawan.updateProfile');
    });
});

// Logout
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login');
})->name('logout');

// Auth tambahan
require __DIR__ . '/auth.php';