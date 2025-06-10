<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  ...$roles
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $userRole = $user->role;

        if (!in_array($userRole, $roles)) {
            return $this->redirectToCorrectDashboard($userRole);
        }

        return $next($request);
    }

    /**
     * Redirect sesuai dengan role
     */
    private function redirectToCorrectDashboard($role)
    {
        switch ($role) {
            case 'admin':
                return redirect()->route('dashboard.admin')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            case 'supervisor':
                return redirect()->route('dashboard.supervisor')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            case 'karyawan':
                return redirect()->route('dashboard.karyawan')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            default:
                return redirect()->route('login')->with('error', 'Role tidak valid.');
        }
    }
}