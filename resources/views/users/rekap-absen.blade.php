@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Rekap Absen</h1>

    @if($presensiRecords->isEmpty())
        <p>No records found.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tanggal</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
                    <th>Lokasi</th>
                    <th>User Agent</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($presensiRecords as $presensi)
                    <tr>
                        <td>{{ $presensi->id }}</td>
                        <td>{{ \Carbon\Carbon::parse($presensi->tanggal)->format('d M Y') }}</td>
                        <td>{{ $presensi->masuk ? \Carbon\Carbon::parse($presensi->masuk)->format('H:i:s') : 'N/A' }}</td>
                        <td>{{ $presensi->pulang ? \Carbon\Carbon::parse($presensi->pulang)->format('H:i:s') : 'N/A' }}</td>
                        <td>{{ $presensi->location ?? 'N/A' }}</td>
                        <td>{{ $presensi->user_agent ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination Links -->
        {{ $presensiRecords->links() }}
    @endif
</div>
@endsection
