@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container">
    <h1 class="mb-4">Admin Dashboard</h1>
    <div class="alert alert-info" role="alert">
        Selamat datang di dashboard admin.
    </div>
    <h2 class="mb-4">Rekap Hadir Hari Ini</h2>
    
    <!-- Tampilkan rekap kehadiran -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($attendances as $attendance)
            <tr>
                <td>{{ $attendance->id }}</td>
                <td>{{ $attendance->user->name }}</td>
                <td>{{ $attendance->masuk }}</td>
                <td>{{ $attendance->pulang }}</td>
                <td>
                    @if($attendance->pulang)
                        Pulang
                    @elseif($attendance->masuk)
                        Hadir
                    @else
                        -
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Tidak ada data hadir hari ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
