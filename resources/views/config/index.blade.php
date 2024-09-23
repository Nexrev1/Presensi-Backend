@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Manage Configuration</div>

                <div class="card-body">
                    <a href="{{ route('config.create') }}" class="btn btn-primary mb-3">Add Configuration</a>

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Jam Masuk</th>
                                <th>Jam Keluar</th>
                                <th>Radius</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($configs as $config)
                                <tr>
                                    <td>{{ $config->jam_masuk }}</td>
                                    <td>{{ $config->jam_keluar }}</td>
                                    <td>{{ $config->radius }}</td>
                                    <td>
                                        <a href="{{ route('config.edit', $config->id) }}" class="btn btn-warning">Edit</a>
                                        <form action="{{ route('config.destroy', $config->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
