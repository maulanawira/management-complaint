<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Login - Complaint Management System')</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">

    {{-- Custom Styles from app.blade.php --}}
    <style>
        body {
            background-color: #ffffff;
            color: #333333; /* Changed from #ffffff to #333333 for better readability */
        }

        .bg-dark-custom {
            background-color: #000000 !important;
        }

        .btn-accent {
            background-color: #1DCD9F;
            color: #000000;
        }

        .btn-accent:hover {
            background-color: #169976;
            color: #ffffff;
        }

        .navbar-brand {
            color: #1DCD9F !important;
        }

        .navbar-brand:hover {
            color: #169976 !important;
        }

        .navbar-logo {
            width: 40px;
            height: 40px;
        }

        .logout-button {
            color: #dc3545 !important;
        }
        
        .logout-button:hover {
            background-color: #dc3545 !important;
            color: white !important;
        }
    </style>

    {{-- Simple Login Styles --}}
    <style>
        .login-container {
            min-height: calc(100vh - 200px);
            background-color: #ffffff;
            padding: 40px 0;
        }

        .login-card {
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid #e9ecef;
            max-width: 400px;
            margin: 0 auto;
        }

        .welcome-title {
            color: #333;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .welcome-subtitle {
            color: #666;
            font-size: 14px;
            margin-bottom: 30px;
        }

        .btn-role {
            background: #ffffff;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 12px;
            text-decoration: none;
            color: #333;
            font-weight: 500;
            font-size: 16px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-role:hover {
            background: #f8f9fa;
            border-color: #1DCD9F;
            color: #1DCD9F;
            text-decoration: none;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(29, 205, 159, 0.2);
        }

        .role-icon {
            width: 24px;
            height: 24px;
            color: #666;
            transition: color 0.3s ease;
        }

        .btn-role:hover .role-icon {
            color: #1DCD9F;
        }

        .logo-circle {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #1DCD9F, #169976);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 24px;
        }
    </style>

    @stack('styles')
</head>
<body>
    {{-- Header/Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark-custom mb-4">
        <div class="container-fluid">
            @php
                // Tentukan route home berdasarkan role user
                $homeRoute = '/';
                if (auth()->check()) {
                    $role = auth()->user()->role;
                    switch ($role) {
                        case 'admin':
                            $homeRoute = route('dashboard.admin');
                            break;
                        case 'supervisor':
                            $homeRoute = route('dashboard.supervisor');
                            break;
                        case 'karyawan':
                            $homeRoute = route('dashboard.karyawan');
                            break;
                        default:
                            $homeRoute = route('dashboard');
                    }
                }
            @endphp
            
            <a class="navbar-brand d-flex align-items-center fw-bold" href="{{ $homeRoute }}">
                {{-- Logo SVG --}}
                <svg class="navbar-logo me-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="2" y="3" width="20" height="14" rx="2" stroke="#1DCD9F" stroke-width="2" fill="none"/>
                    <path d="m22 7-10 5L2 7" stroke="#1DCD9F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M8 21h8" stroke="#1DCD9F" stroke-width="2" stroke-linecap="round"/>
                    <path d="M12 17v4" stroke="#1DCD9F" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <span>Complaint Management System</span>
            </a>
            
            <div class="d-flex">
                @auth
                    <div class="dropdown">
                        <button class="btn btn-sm dropdown-toggle btn-accent" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-1"></i>
                            {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <h6 class="dropdown-header">
                                    <i class="bi bi-person-badge me-1"></i>
                                    {{ ucfirst(Auth::user()->role) }}
                                </h6>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                @php
                                $role = Auth::user()->role ?? 'guest';
                                switch ($role) {
                                    case 'admin':
                                        $profileRoute = route('admin.editProfile');
                                        break;
                                    case 'supervisor':
                                        $profileRoute = route('supervisor.editProfile');
                                        break;
                                    case 'karyawan':
                                        $profileRoute = route('karyawan.editProfile');
                                        break;
                                    default:
                                        $profileRoute = '#';
                                }
                                @endphp
                                <a class="dropdown-item" href="{{ $profileRoute }}">
                                    <i class="bi bi-person-gear me-2"></i>Edit Profil
                                </a>
                            </li>
                            
                            @if(Auth::user()->role === 'karyawan')
                            <li>
                                <a class="dropdown-item" href="{{ route('dashboard.karyawan.history') }}">
                                    <i class="bi bi-clock-history me-2"></i>Riwayat Komplain
                                </a>
                            </li>
                            @endif
                            
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item logout-button">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-accent btn-sm">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Login
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <div class="container">
        {{-- Alert Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </div>

    {{-- Footer --}}
    <footer class="bg-dark-custom text-white text-center py-3 mt-5">
        <div class="container">
            <small>&copy; {{ date('Y') }} Complaint Management System. All rights reserved.</small>
        </div>
    </footer>

    {{-- Bootstrap JS Bundle --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>

    {{-- Optional: Custom Scripts --}}
    @stack('scripts')
</body>
</html>