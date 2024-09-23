@extends('layouts.app')

@section('title', 'Manage Izin')

@section('content')
<div class="container">
    <h1 class="mb-4">Manage Izin</h1>
    
    <!-- Tampilkan pesan sukses jika ada -->
    @if(session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="alert alert-info" role="alert">
        Kelola izin karyawan di sini.
    </div>
    
    <!-- Tampilkan tabel izin -->
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Karyawan</th>
                    <th>Jenis Izin</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Lama Waktu Izin</th> <!-- Kolom Lama Waktu Izin -->
                    <th>Status</th>
                    <th>Aksi</th> <!-- Kolom Aksi -->
                </tr>
            </thead>
            <tbody>
                @foreach ($izin as $i)
                <tr>
                    <td>{{ $i->id }}</td>
                    <td>{{ $i->user->name }}</td>
                    <td>{{ $i->jenis_izin }}</td>
                    <td>{{ \Carbon\Carbon::parse($i->tanggal_mulai)->format('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($i->tanggal_selesai)->format('d M Y') }}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($i->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($i->tanggal_selesai)) }} hari
                    </td>
                    <td>
                        @switch($i->status)
                            @case('approved')
                                Disetujui
                                @break
                            @case('rejected')
                                Ditolak
                                @break
                            @default
                                Menunggu
                        @endswitch
                    </td>
                    <td>
                        @if($i->status === 'pending')
                            <form action="{{ route('admin.approve-izin', $i->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui izin ini?');">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Setujui</button>
                            </form>
                            <form action="{{ route('admin.reject-izin', $i->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menolak izin ini?');">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                            </form>
                        @endif
                        @if($i->dokumen)
                            <a href="{{ asset('storage/' . $i->dokumen) }}" class="btn btn-info btn-sm" target="_blank">Lihat Dokumen</a>
                        @endif
                    </td>
                </tr>
                @endforeach
                @if($izin->isEmpty())
                <tr>
                    <td colspan="8" class="text-center">Tidak ada izin yang diajukan.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
