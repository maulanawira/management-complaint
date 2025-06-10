@extends('layouts.app')

@section('title', 'Karyawan Dashboard')

@section('content')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/karyawan-styles.css') }}">
@endpush

<head>
  ...
  @stack('styles')
</head>

<div class="row">
    {{-- Sidebar --}}
    <div class="col-md-3">
        <div class="list-group">
            <a href="{{ route('dashboard.karyawan') }}" 
               class="list-group-item list-group-item-action active"
               style="background-color: #000000; color: #ffffff;">
                Ajukan Komplain
            </a>
            <a href="{{ route('dashboard.karyawan.history') }}" 
               class="list-group-item list-group-item-action sidebar-link">
                Riwayat Komplain
            </a>
        </div>
    </div>

    {{-- Konten Utama --}}
    <div class="col-md-9">
        <h2 class="mb-4" style="color: #000000;">Ajukan Komplain Baru</h2>

        {{-- Flash message sukses --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-header" style="background-color: #169976; color: #ffffff;">
                Formulir Komplain
            </div>
            <div class="card-body" style="background-color: #ffffff; border: 1px solid #169976; border-radius: 4px;">
                <form action="{{ route('complaints.store') }}" method="POST">
                    @csrf

                    {{-- Jenis Komplain --}}
                    <div class="mb-3">
                        <label for="jenis" class="form-label" style="color: #000000;">Jenis Komplain</label>
                        <select class="form-select uniform-field" name="jenis" id="jenis" required>
                            <option value="" disabled selected>Pilih jenis komplain</option>
                            <option value="Gaji dan Tunjangan">Gaji dan Tunjangan</option>
                            <option value="Fasilitas Kerja">Fasilitas Kerja</option>
                            <option value="Diskriminasi atau Pelecehan">Diskriminasi atau Pelecehan</option>
                            <option value="Waktu kerja tidak fleksibel">Waktu kerja tidak fleksibel</option>
                            <option value="Kurangnya penghargaan/kepedulian">Kurangnya penghargaan/kepedulian</option>
                            <option value="Masalah komunikasi">Masalah komunikasi</option>
                        </select>
                    </div>

                    {{-- Judul Komplain --}}
                    <div class="mb-3">
                        <label for="judul" class="form-label" style="color: #000000;">Judul Komplain</label>
                        <input type="text" name="judul" id="judul" class="form-control uniform-field" required>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label" style="color: #000000;">Deskripsi Komplain</label>
                        <textarea name="deskripsi" id="deskripsi" rows="4" class="form-control uniform-field" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-lg" style="background-color: #1DCD9F; color: #000000;">
                        Kirim
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection