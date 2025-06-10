@extends('layouts.app')

@section('title', 'Supervisor Dashboard')

@section('content')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/supervisor-styles.css') }}">
@endpush

<div class="d-flex flex-column min-vh-100">
    <div class="flex-grow-1 container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 style="color: #000000; text-align: left;">Dashboard Supervisor</h2>
            
            {{-- Export Button with Filter - Moved further right --}}
            <div class="dropdown ms-auto">
                <button class="btn dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" 
                        style="background-color: #169976; color: white; border: none;">
                    <i class="fas fa-download me-2"></i>Export Laporan
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="exportDropdown">
                    <li><a class="dropdown-item" href="{{ route('dashboards.export.summary') }}">Export Semua Data</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#exportFilterModal">Export dengan Filter Tanggal</a></li>
                </ul>
            </div>
        </div>

        {{-- Export Filter Modal --}}
        <div class="modal fade" id="exportFilterModal" tabindex="-1" aria-labelledby="exportFilterModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #169976; color: #ffffff;">
                        <h5 class="modal-title" id="exportFilterModalLabel">Filter Export Laporan</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('dashboards.export.filtered') }}" method="GET">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="end_date" class="form-label">Tanggal Akhir</label>
                                        <input type="date" class="form-control" id="end_date" name="end_date" required>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="status_filter" class="form-label">Status (Opsional)</label>
                                <select class="form-select" id="status_filter" name="status">
                                    <option value="">Semua Status</option>
                                    <option value="baru">Baru</option>
                                    <option value="diproses">Diproses</option>
                                    <option value="selesai">Selesai</option>
                                    <option value="ditolak">Ditolak</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="jenis_filter" class="form-label">Jenis Komplain (Opsional)</label>
                                <select class="form-select" id="jenis_filter" name="jenis">
                                    <option value="">Semua Jenis</option>
                                    <option value="Gaji dan Tunjangan">Gaji dan Tunjangan</option>
                                    <option value="Fasilitas Kerja">Fasilitas Kerja</option>
                                    <option value="Diskriminasi atau Pelecehan">Diskriminasi atau Pelecehan</option>
                                    <option value="Waktu kerja tidak fleksibel">Waktu kerja tidak fleksibel</option>
                                    <option value="Kurangnya penghargaan/kepedulian">Kurangnya penghargaan/kepedulian</option>
                                    <option value="Masalah komunikasi">Masalah komunikasi</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn" style="background-color: #169976; color: white;">
                                <i class="fas fa-download me-2"></i>Export PDF
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Statistics Cards --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center shadow-sm" style="border-left: 4px solid #169976;">
                    <div class="card-body">
                        <h5 class="card-title" style="color: #169976;">Total Komplain</h5>
                        <h2 class="display-4 fw-bold" style="color: #000000;">
                            {{ $totalComplaints ?? 0 }}
                        </h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center shadow-sm" style="border-left: 4px solid #dc3545;">
                    <div class="card-body">
                        <h5 class="card-title" style="color: #dc3545;">Belum Diproses</h5>
                        <h2 class="display-4 fw-bold" style="color: #000000;">
                            {{ $pendingComplaints ?? 0 }}
                        </h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center shadow-sm" style="border-left: 4px solid #17a2b8;">
                    <div class="card-body">
                        <h5 class="card-title" style="color: #17a2b8;">Sedang Diproses</h5>
                        <h2 class="display-4 fw-bold" style="color: #000000;">
                            {{ $processingComplaints ?? 0 }}
                        </h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center shadow-sm" style="border-left: 4px solid #28a745;">
                    <div class="card-body">
                        <h5 class="card-title" style="color: #28a745;">Selesai</h5>
                        <h2 class="display-4 fw-bold" style="color: #000000;">
                            {{ $completedComplaints ?? 0 }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content - All Complaints Table --}}
        <div class="card shadow-sm border-0" style="border: 2px solid #1DCD9F; border-radius: 8px;">
            <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #169976; color: #ffffff;">
                <span>Semua Data Komplain</span>
                <button class="btn btn-sm btn-light" onclick="window.print()" title="Print Tabel">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
            <div class="card-body" style="background-color: #ffffff;">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle" id="complaintsTable">
                        <thead style="background-color: #1DCD9F; color: #ffffff;">
                            <tr>
                                <th>No</th>
                                <th>ID Komplain</th>
                                <th>Nama Karyawan</th>
                                <th>Jenis</th>
                                <th>Judul</th>
                                <th>Status</th>
                                <th>Tanggal Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($allComplaints ?? [] as $complaint)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td style="color: #000000;">{{ $complaint->id_komplain }}</td>
                                    <td style="color: #000000;">{{ $complaint->user->name ?? '—' }}</td>
                                    <td style="color: #000000;">{{ $complaint->jenis }}</td>
                                    <td style="color: #000000;">{{ Str::limit($complaint->judul, 30) }}</td>
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
                                        {{ $complaint->created_at->format('d-m-Y H:i') }}
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm" 
                                                style="background-color: #1DCD9F; color: #ffffff;"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#detailModal{{ $complaint->id }}">
                                            Detail
                                        </button>

                                        {{-- Detail Modal --}}
                                        <div class="modal fade" id="detailModal{{ $complaint->id }}" tabindex="-1" 
                                             aria-labelledby="detailModalLabel{{ $complaint->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header" style="background-color: #169976; color: #ffffff;">
                                                        <h5 class="modal-title" id="detailModalLabel{{ $complaint->id }}">
                                                            Detail Komplain
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white" 
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <strong style="color: #000000;">ID Komplain:</strong><br>
                                                                <span style="color: #000000;">{{ $complaint->id_komplain }}</span>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <strong style="color: #000000;">Karyawan:</strong><br>
                                                                <span style="color: #000000;">{{ $complaint->user->name ?? '—' }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <strong style="color: #000000;">Jenis:</strong><br>
                                                                <span style="color: #000000;">{{ $complaint->jenis }}</span>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <strong style="color: #000000;">Status:</strong><br>
                                                                <span class="badge 
                                                                    @if($complaint->status === 'baru') bg-warning
                                                                    @elseif($complaint->status === 'diproses') bg-info
                                                                    @elseif($complaint->status === 'selesai') bg-success
                                                                    @elseif($complaint->status === 'ditolak') bg-danger
                                                                    @else bg-secondary @endif">
                                                                    {{ ucfirst($complaint->status) }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <strong style="color: #000000;">Dibuat:</strong><br>
                                                                <span style="color: #000000;">{{ $complaint->created_at->format('d-m-Y H:i') }}</span>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <strong style="color: #000000;">Diupdate:</strong><br>
                                                                <span style="color: #000000;">{{ $complaint->updated_at->format('d-m-Y H:i') }}</span>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="mb-3">
                                                            <strong style="color: #000000;">Judul:</strong><br>
                                                            <p style="color: #000000;">{{ $complaint->judul }}</p>
                                                        </div>
                                                        <div class="mb-3">
                                                            <strong style="color: #000000;">Deskripsi:</strong><br>
                                                            <p style="color: #000000;">{{ $complaint->deskripsi }}</p>
                                                        </div>
                                                        @if($complaint->feedback)
                                                            <div class="mb-3">
                                                                <strong style="color: #000000;">Feedback Admin:</strong><br>
                                                                <div class="alert alert-info" style="background-color: #e7f3ff; border: 1px solid #169976;">
                                                                    <span style="color: #000000;">{{ $complaint->feedback }}</span>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                            Tutup
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">
                                        Tidak ada data komplain.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Summary Section --}}
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card shadow-sm border-0" style="border: 2px solid #1DCD9F; border-radius: 8px;">
                    <div class="card-header" style="background-color: #169976; color: #ffffff;">
                        Ringkasan Jenis Komplain
                    </div>
                    <div class="card-body" style="background-color: #ffffff;">
                        <div class="row">
                            <div class="col-12">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span style="color: #000000;">Gaji dan Tunjangan</span>
                                        <span class="badge bg-primary rounded-pill">{{ $complaintsByType['Gaji dan Tunjangan'] ?? 0 }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span style="color: #000000;">Fasilitas Kerja</span>
                                        <span class="badge bg-primary rounded-pill">{{ $complaintsByType['Fasilitas Kerja'] ?? 0 }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span style="color: #000000;">Diskriminasi atau Pelecehan</span>
                                        <span class="badge bg-primary rounded-pill">{{ $complaintsByType['Diskriminasi atau Pelecehan'] ?? 0 }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span style="color: #000000;">Waktu kerja tidak fleksibel</span>
                                        <span class="badge bg-primary rounded-pill">{{ $complaintsByType['Waktu kerja tidak fleksibel'] ?? 0 }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span style="color: #000000;">Kurangnya penghargaan/kepedulian</span>
                                        <span class="badge bg-primary rounded-pill">{{ $complaintsByType['Kurangnya penghargaan/kepedulian'] ?? 0 }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span style="color: #000000;">Masalah komunikasi</span>
                                        <span class="badge bg-primary rounded-pill">{{ $complaintsByType['Masalah komunikasi'] ?? 0 }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm border-0" style="border: 2px solid #1DCD9F; border-radius: 8px;">
                    <div class="card-header" style="background-color: #169976; color: #ffffff;">
                        Aktivitas Terbaru
                    </div>
                    <div class="card-body" style="background-color: #ffffff;">
                        <div class="list-group list-group-flush">
                            @forelse ($recentActivity ?? [] as $activity)
                                <div class="list-group-item border-0 px-0">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1" style="color: #000000;">{{ $activity->judul ?? 'Komplain Baru' }}</h6>
                                        <small style="color: #6c757d;">{{ $activity->created_at->diffForHumans() ?? '1 jam lalu' }}</small>
                                    </div>
                                    <p class="mb-1" style="color: #000000;">{{ $activity->user->name ?? 'Karyawan' }} - {{ $activity->jenis ?? 'Gaji dan Tunjangan' }}</p>
                                    <small style="color: #6c757d;">Status: {{ ucfirst($activity->status ?? 'baru') }}</small>
                                </div>
                            @empty
                                <div class="list-group-item border-0 px-0">
                                    <p class="text-muted mb-0">Tidak ada aktivitas terbaru.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set default dates (last 30 days)
    const today = new Date();
    const lastMonth = new Date(today.getTime() - (30 * 24 * 60 * 60 * 1000));
    
    document.getElementById('end_date').value = today.toISOString().split('T')[0];
    document.getElementById('start_date').value = lastMonth.toISOString().split('T')[0];
    
    // Validate date range
    document.getElementById('start_date').addEventListener('change', function() {
        const startDate = new Date(this.value);
        const endDate = new Date(document.getElementById('end_date').value);
        
        if (startDate > endDate) {
            document.getElementById('end_date').value = this.value;
        }
    });
    
    document.getElementById('end_date').addEventListener('change', function() {
        const startDate = new Date(document.getElementById('start_date').value);
        const endDate = new Date(this.value);
        
        if (endDate < startDate) {
            document.getElementById('start_date').value = this.value;
        }
    });
});
</script>
@endsection