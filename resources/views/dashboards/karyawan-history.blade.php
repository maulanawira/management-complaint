@extends('layouts.app')

@section('title', 'Riwayat Komplain')

@section('content')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/karyawan-history-styles.css') }}">
@endpush

<head>
  ...
  @stack('styles')
</head>


<div class="d-flex flex-column min-vh-100">
    <div class="row flex-grow-1">
        {{-- Sidebar --}}
        <div class="col-md-3">
            <div class="list-group">
                <a href="{{ route('dashboard.karyawan') }}" 
                   class="list-group-item list-group-item-action sidebar-link">
                    Ajukan Komplain
                </a>
                <a href="{{ route('dashboard.karyawan.history') }}" 
                   class="list-group-item list-group-item-action active">
                    Riwayat Komplain
                </a>
            </div>
        </div>

        {{-- Konten Utama --}}
        <div class="col-md-9">
            <h2 class="mb-4" style="color: #000000;">Riwayat Komplain Anda</h2>

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead style="background-color: #222222; color: #ffffff;">
                        <tr>
                            <th>No</th>
                            <th>ID Komplain</th>
                            <th>Jenis Komplain</th>
                            <th>Judul</th>
                            <th>Deskripsi</th>
                            <th>Status</th>
                            <th>Dibuat</th>
                            <th>Feedback</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($complaints as $complaint)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td style="color: #000000;">{{ $complaint->id_komplain }}</td>
                                <td style="color: #000000;">{{ $complaint->jenis }}</td>
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
                                <td style="color: #000000;">{{ $complaint->created_at->format('d-m-Y') }}</td>
                                <td style="color: #000000;">
                                    {{ $complaint->feedback ?? '-' }}
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete('{{ route('complaints.destroy', $complaint->id) }}')">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">Belum ada komplain.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal Konfirmasi Hapus --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="border: 2px solid #169976;">
      <div class="modal-header" style="background-color: #000000;">
        <h5 class="modal-title" id="deleteModalLabel" style="color: #ffffff;">Konfirmasi Hapus</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="color: #000000;">
        Apakah Anda yakin ingin menghapus komplain ini?
      </div>
      <div class="modal-footer" style="background-color: #f8f9fa;">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <form id="deleteForm" method="POST" action="">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Hapus</button>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- Script modal --}}
@push('scripts')
<script>
    function confirmDelete(actionUrl) {
        const form = document.getElementById('deleteForm');
        form.action = actionUrl;
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }
</script>
@endpush
@endsection