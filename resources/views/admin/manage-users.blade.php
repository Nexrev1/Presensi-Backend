@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
<div class="container">
    <h1>Manage Karyawan</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Search Form -->
    <form action="{{ route('admin.manage-users') }}" method="GET" class="mb-4">
        <div class="form-group">
            <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <!-- Tabel data pengguna -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>NIK</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role }}</td>
                <td>{{ $user->nik }}</td>
                <td class="actions">
                    <a href="{{ route('admin.edit-user', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('admin.delete-user', $user->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada pengguna.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <!-- Pagination Links -->
    {{ $users->links() }}
</div>
@endsection

@section('styles')
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .table th {
            background-color: #f4f4f4;
        }
        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .actions {
            display: flex;
            gap: 10px;
        }
        .actions a, .actions button {
            padding: 5px 10px;
            text-decoration: none;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        .actions a {
            background-color: #ffc107;
        }
        .actions button {
            background-color: #dc3545;
        }
    </style>
@endsection
