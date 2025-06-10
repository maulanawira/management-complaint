@extends('layouts.login')

@section('title', 'Pilih Login')

@section('content')
<div class="login-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 col-lg-5">
                <div class="card login-card">
                    <div class="card-body p-4">

                        <!-- Header -->
                        <div class="text-center">
                            <div class="logo-circle">
                                <i class="fas fa-users"></i>
                            </div>
                            <h2 class="welcome-title">Selamat Datang</h2>
                            <p class="welcome-subtitle">Pilih peran Anda untuk melanjutkan</p>
                        </div>

                        <!-- Role Buttons -->
                        <div class="d-grid">
                            <a href="{{ route('login.admin') }}" class="btn btn-role">
                                <i class="fas fa-user-shield role-icon"></i>
                                <span>Admin</span>
                            </a>
                            <a href="{{ route('login.karyawan') }}" class="btn btn-role">
                                <i class="fas fa-user role-icon"></i>
                                <span>Karyawan</span>
                            </a>
                            <a href="{{ route('login.supervisor') }}" class="btn btn-role">
                                <i class="fas fa-user-tie role-icon"></i>
                                <span>Supervisor</span>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
