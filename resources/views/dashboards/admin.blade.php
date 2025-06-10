@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-styles.css') }}">
@endpush

<head>
  ...
  @stack('styles')
</head>


<div class="d-flex flex-column min-vh-100">
    <div class="flex-grow-1 container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 style="color: #000000; text-align: left;">Data Komplain</h2>
            
            {{-- Tombol Kelola Akun --}}
            <button type="button" class="btn btn-kelola-akun" data-bs-toggle="modal" data-bs-target="#kelolaAkunModal">
                <i class="fas fa-users"></i> Kelola Akun
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead style="background-color: #1DCD9F; color: #ffffff;">
                    <tr>
                        <th>No</th>
                        <th>ID Komplain</th>
                        <th>Nama Karyawan</th>
                        <th>Judul Komplain</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th>Feedback</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($complaints as $complaint)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td style="color: #000000;">{{ $complaint->id_komplain }}</td>
                            <td style="color: #000000;">{{ $complaint->user->name ?? '—' }}</td>
                            <td style="color: #000000;">{{ $complaint->judul }}</td>
                            <td style="color: #000000;">{{ $complaint->deskripsi }}</td>
                            <td>
                                <span class="badge 
                                    @if($complaint->status === 'baru') bg-warning
                                    @elseif($complaint->status === 'diproses') bg-info
                                    @elseif($complaint->status === 'selesai') bg-success
                                    @elseif($complaint->status === 'ditolak') bg-danger
                                    @else bg-secondary @endif">
                                    {{ ucfirst($complaint->status) }}
                                </span>
                            </td>
                            <td style="color: #000000;">
                                @if($complaint->feedback)
                                    {{ $complaint->feedback }}
                                @else
                                    <em>Belum ada feedback</em>
                                @endif
                            </td>
                            <td style="color: #000000;">{{ $complaint->created_at->format('d-m-Y') }}</td>
                            <td>
                                {{-- Ubah Status --}}
                                <form action="{{ route('complaints.updateStatus', $complaint->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="form-select form-select-sm mb-1 custom-dropdown"
                                        onchange="this.form.submit()">
                                        <option value="baru" {{ $complaint->status == 'baru' ? 'selected' : '' }}>Baru</option>
                                        <option value="diproses" {{ $complaint->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                        <option value="selesai" {{ $complaint->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                        <option value="ditolak" {{ $complaint->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                    </select>
                                </form>

                                {{-- Tombol Feedback --}}
                                <button type="button" class="btn btn-sm mt-1 btn-feedback"
                                    data-bs-toggle="modal" data-bs-target="#feedbackModal{{ $complaint->id }}">
                                    Feedback
                                </button>

                                {{-- Modal Feedback --}}
                                <div class="modal fade" id="feedbackModal{{ $complaint->id }}" tabindex="-1" aria-labelledby="feedbackModalLabel{{ $complaint->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form action="{{ route('complaints.feedback', $complaint->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="feedbackModalLabel{{ $complaint->id }}">Feedback Komplain</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <textarea name="feedback" class="form-control" rows="4" required
                                                        style="background-color: #ffffff; color: #000000;">{{ old('feedback', $complaint->feedback) }}</textarea>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-success">Simpan Feedback</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">Belum ada data komplain.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Kelola Akun --}}
<div class="modal fade" id="kelolaAkunModal" tabindex="-1" aria-labelledby="kelolaAkunModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="kelolaAkunModalLabel">
                    <i class="fas fa-users"></i> Kelola Akun
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- Nav Pills untuk Tab --}}
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pills-karyawan-tab" data-bs-toggle="pill" data-bs-target="#pills-karyawan" type="button" role="tab" aria-controls="pills-karyawan" aria-selected="true">
                            <i class="fas fa-user"></i> Tambah Karyawan
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-supervisor-tab" data-bs-toggle="pill" data-bs-target="#pills-supervisor" type="button" role="tab" aria-controls="pills-supervisor" aria-selected="false">
                            <i class="fas fa-user-tie"></i> Tambah Supervisor
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-admin-tab" data-bs-toggle="pill" data-bs-target="#pills-admin" type="button" role="tab" aria-controls="pills-admin" aria-selected="false">
                            <i class="fas fa-user-shield"></i> Tambah Admin
                        </button>
                    </li>
                </ul>
                
                {{-- Tab Content --}}
                <div class="tab-content" id="pills-tabContent">
                    {{-- Form Tambah Karyawan --}}
                    <div class="tab-pane fade show active" id="pills-karyawan" role="tabpanel" aria-labelledby="pills-karyawan-tab">
                        <form action="{{ route('admin.createKaryawan') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="karyawan_name" class="form-label text-black">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control text-black" id="karyawan_name" name="name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="karyawan_email" class="form-label text-black">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control text-black" id="karyawan_email" name="email" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="karyawan_phone" class="form-label text-black">No. Telepon</label>
                                    <input type="tel" class="form-control text-black" id="karyawan_phone" name="phone">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="karyawan_password" class="form-label text-black">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control text-black" id="karyawan_password" name="password" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="karyawan_address" class="form-label text-black">Alamat</label>
                                    <textarea class="form-control text-black" id="karyawan_address" name="address" rows="3"></textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="karyawan_department" class="form-label text-black">Departemen <span class="text-danger">*</span></label>
                                    <select class="form-select text-black" id="karyawan_department" name="department" required>
                                        <option value="">Pilih Departemen</option>
                                        <option value="Produksi">Produksi</option>
                                        <option value="IT">IT</option>
                                        <option value="General Affairs">General Affairs</option>
                                        <option value="Human Resources">Human Resources</option>
                                        <option value="Keuangan">Keuangan</option>
                                        <option value="Sales/Marketing">Sales/Marketing</option>
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" name="role" value="karyawan">
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Tambah Karyawan
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    {{-- Form Tambah Supervisor --}}
                    <div class="tab-pane fade" id="pills-supervisor" role="tabpanel" aria-labelledby="pills-supervisor-tab">
                        <form action="{{ route('admin.createSupervisor') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="supervisor_name" class="form-label text-black">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control text-black" id="supervisor_name" name="name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="supervisor_email" class="form-label text-black">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control text-black" id="supervisor_email" name="email" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="supervisor_phone" class="form-label text-black">No. Telepon</label>
                                    <input type="tel" class="form-control text-black" id="supervisor_phone" name="phone">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="supervisor_password" class="form-label text-black">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control text-black" id="supervisor_password" name="password" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="supervisor_address" class="form-label text-black">Alamat</label>
                                    <textarea class="form-control text-black" id="supervisor_address" name="address" rows="3"></textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="supervisor_department" class="form-label text-black">Departemen <span class="text-danger">*</span></label>
                                    <select class="form-select text-black" id="supervisor_department" name="department" required>
                                        <option value="">Pilih Departemen</option>
                                        <option value="Produksi">Produksi</option>
                                        <option value="IT">IT</option>
                                        <option value="General Affairs">General Affairs</option>
                                        <option value="Human Resources">Human Resources</option>
                                        <option value="Keuangan">Keuangan</option>
                                        <option value="Sales/Marketing">Sales/Marketing</option>
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" name="role" value="supervisor">
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Tambah Supervisor
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    {{-- Form Tambah Admin --}}
                    <div class="tab-pane fade" id="pills-admin" role="tabpanel" aria-labelledby="pills-admin-tab">
                        <form action="{{ route('admin.createAdmin') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="admin_name" class="form-label text-black">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control text-black" id="admin_name" name="name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="admin_email" class="form-label text-black">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control text-black" id="admin_email" name="email" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="admin_phone" class="form-label text-black">No. Telepon</label>
                                    <input type="tel" class="form-control text-black" id="admin_phone" name="phone">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="admin_password" class="form-label text-black">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control text-black" id="admin_password" name="password" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="admin_address" class="form-label text-black">Alamat</label>
                                    <textarea class="form-control text-black" id="admin_address" name="address" rows="3"></textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="admin_department" class="form-label text-black">Departemen <span class="text-danger">*</span></label>
                                    <select class="form-select text-black" id="admin_department" name="department" required>
                                        <option value="">Pilih Departemen</option>
                                        <option value="Produksi">Produksi</option>
                                        <option value="IT">IT</option>
                                        <option value="General Affairs">General Affairs</option>
                                        <option value="Human Resources">Human Resources</option>
                                        <option value="Keuangan">Keuangan</option>
                                        <option value="Sales/Marketing">Sales/Marketing</option>
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" name="role" value="admin">
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Tambah Admin
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection