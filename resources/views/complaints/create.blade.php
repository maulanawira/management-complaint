@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Buat Komplain Baru</h2>

    <form action="{{ route('complaints.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="subject" class="form-label">Subjek</label>
            <input type="text" class="form-control" id="subject" name="subject" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Deskripsi</label>
            <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-success">Kirim Komplain</button>
    </form>
</div>
@endsection