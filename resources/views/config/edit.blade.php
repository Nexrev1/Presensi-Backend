@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ isset($config) ? 'Edit Configuration' : 'Add Configuration' }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ isset($config) ? route('config.update', $config->id) : route('config.store') }}">
                        @csrf
                        @if(isset($config))
                            @method('PUT')
                        @endif

                        <div class="form-group">
                            <label for="jam_masuk">Jam Masuk</label>
                            <input type="text" class="form-control" id="jam_masuk" name="jam_masuk" value="{{ $config->jam_masuk ?? '' }}" required>
                        </div>

                        <div class="form-group">
                            <label for="jam_keluar">Jam Keluar</label>
                            <input type="text" class="form-control" id="jam_keluar" name="jam_keluar" value="{{ $config->jam_keluar ?? '' }}" required>
                        </div>

                        <div class="form-group">
                            <label for="radius">Radius</label>
                            <input type="text" class="form-control" id="radius" name="radius" value="{{ $config->radius ?? '' }}" required>
                        </div>

                        <button type="submit" class="btn btn-primary">{{ isset($config) ? 'Update' : 'Submit' }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
