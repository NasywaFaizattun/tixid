@extends('template.app')

@section('content')

    <div class="container align-items-center w-75 d-block mx-auto my-5 p-4">
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="">Pengguna</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.users.index')}}">Data</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="">Tambah</a>
                </li>
            </ol>
            </nav>
        </div>
        </nav>

        <form method="POST" action="{{ route('admin.users.store')}}" class="card my-4 p-4">
            <h5 class="text-center my-3">Buat Data Staff</h5>
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nama Lengkap :</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name">
                @error('name')
                    <small class="text-danger">*{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Email :</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email">
                @error('email')
                    <small class="text-danger">*{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="name" class="form-label @error('password') is-invalid @enderror">Password :</label>
                <input type="password" class="form-control" id="password" name="password">
                @error('password')
                    <small class="text-danger">*{{ $message }}</small>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">BUAT</button>
        </form>
    </div>


@endsection
