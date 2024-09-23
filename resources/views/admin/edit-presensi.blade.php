@extends('layouts.app')

@section('content')
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('admin.update-presensi', $presensi->id) }}" method="POST">
            @csrf
            @method('PUT') <!-- Menggunakan PUT karena rute update dengan metode PUT -->
            <div class="form-group">
                <label for="tanggal">Tanggal</label>
                <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ old('tanggal', $presensi->tanggal) }}" required>
            </div>
            <div class="form-group">
                <label for="masuk">Jam Masuk</label>
                <input type="time" name="masuk" id="masuk" class="form-control" value="{{ old('masuk', $presensi->masuk) }}" required>
            </div>
            <div class="form-group">
                <label for="pulang">Jam Pulang</label>
                <input type="time" name="pulang" id="pulang" class="form-control" value="{{ old('pulang', $presensi->pulang) }}">
            </div>
            <button type="submit" class="btn btn-primary">Update Presensi</button>
        </form>
    </div>
@endsection
