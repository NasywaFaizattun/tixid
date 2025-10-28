@extends('template.app')

@section('content')
    <div class="card my-5 p-5 w-75 d-blox mx-auto">
        <form method="POST" action="{{ route('admin.cinemas.store')}}">
            <h5 class="text-center my-3">Tambah Data Bioskop</h5>
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nama Bioskop</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name">
                @error('name')
                    <small class="text-danger">*{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Lokasi :</label>
                <textarea id="location" cols="30" rows="5" class="form-control @error('location') is-invalid @enderror" name="location"></textarea>
                @error('location')
                    <small class="text-danger">*{{ $message }}</small>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary w-100">Tambah Data</button>
        </form>
    </div>

@endsection
