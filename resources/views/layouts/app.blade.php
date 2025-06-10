<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Complaint Management System')</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    {{-- Custom Styles --}}
    <style>
        body {
            background-color: #ffffff;
            color: #ffffff;
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