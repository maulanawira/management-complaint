@extends('layouts.login')

@section('title', 'Login Karyawan')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/login-karyawan-styles.css') }}">
@endpush

@section('content')
<div class="login-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 col-lg-5">
                <div class="card login-card">
                    <div class="card-body p-4">

                        <!-- Tombol Kembali -->
                        <div class="mb-3">
                            <a href="{{ route('login') }}" class="btn btn-back">
                                <i class="fas fa-arrow-left"></i>
                                <span>Kembali</span>
                            </a>
                        </div>

                        <!-- Header -->
                        <div class="text-center mb-4">
                            <div class="logo-circle">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <h2 class="welcome-title">Login Karyawan</h2>
                            <p class="welcome-subtitle">Masuk sebagai Karyawan</p>
                        </div>

                        <!-- Form Login -->
                        <form method="POST" action="{{ route('login.custom') }}">
                            @csrf
                            <input type="hidden" name="role" value="karyawan">

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input id="email" type="email" name="email" required autofocus
                                    class="form-control login-input"
                                    placeholder="Masukkan email Anda" />
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <div class="position-relative">
                                    <input id="password" type="password" name="password" required
                                        class="form-control login-input pe-5"
                                        placeholder="Masukkan password Anda" />
                                    <span class="position-absolute top-50 end-0 translate-middle-y me-3"
                                          onclick="togglePassword()" style="cursor: pointer;">
                                        <i id="toggleIcon" class="fas fa-eye text-muted"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-login">
                                    <i class="fas fa-sign-in-alt me-2"></i>
                                    Login
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toggle Password Visibility -->
<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }
</script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if($errors->has('email'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Login Gagal',
        text: 'Email atau password salah, silahkan coba lagi',
        confirmButtonColor: '#1DCD9F'
    });
</script>
@endif
@endsection