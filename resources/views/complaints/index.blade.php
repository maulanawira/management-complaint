@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Daftar Komplain Anda</h2>

    <a href="{{ route('complaints.create') }}" class="btn btn-primary mb-3">+ Buat Komplain</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Subjek</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($complaints as $complaint)
            <tr>
                <td>{{ $complaint->subject }}</td>
                <td>{{ ucfirst($complaint->status) }}</td>
                <td>{{ $complaint->created_at->format('d M Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection