@extends('layouts.app')

@section('title', 'Edit Profil Supervisor')

@section('content')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/edit-supervisor-styles.css') }}">
@endpush

<div class="d-flex flex-column min-vh-100">
    <div class="row flex-grow-1">
        <div class="col-12">
            <h2 class="mb-4" style="color: #000000;">Edit Profil Supervisor</h2>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <script>
                    alert("Gagal ubah data, pastikan memasukkan karakter yang benar.");
                </script>
            @endif

            <div class="card shadow-sm border-0 p-4" style="background-color: #ffffff;">
                <form action="{{ route('supervisor.updateProfile') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label" style="color: #000000;">Nama</label>
                        <input type="text" class="form-control form-disabled" id="name" name="name"
                            value="{{ Auth::user()->name }}" readonly disabled>
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label" style="color: #000000;">Alamat</label>
                        <textarea class="form-control form-white-text" id="alamat" name="alamat" rows="3"
                            placeholder="{{ Auth::user()->address ?? 'Masukkan alamat lengkap' }}">{{ old('alamat') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="no_handphone" class="form-label" style="color: #000000;">No. Handphone</label>
                        <input type="text" class="form-control form-white-text" id="no_handphone" name="no_handphone"
                            value="{{ old('no_handphone') }}" 
                            placeholder="{{ Auth::user()->phone ?? 'Contoh: 08123456789' }}">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label" style="color: #000000;">Password Baru</label>
                        <input type="password" class="form-control form-white-text" id="password" name="password"
                            placeholder="Kosongkan jika tidak ingin mengubah password">
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label" style="color: #000000;">Konfirmasi Password</label>
                        <input type="password" class="form-control form-white-text" id="password_confirmation" name="password_confirmation"
                            placeholder="Ulangi password baru">
                    </div>

                    <div class="d-flex justify-content-start gap-3">
                        <a href="{{ route('dashboard.supervisor') }}" class="btn btn-cancel">Cancel</a>
                        <button type="submit" class="btn btn-accent">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection