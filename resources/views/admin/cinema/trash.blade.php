@extends('template.app')

@section('content')

    <div class="container my-3">
        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.cinemas.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    <h3 class="my-3">Data Sampah Cinema</h3>
    @if (Session::get('success'))
        <div class="alert alert-success">{{ Session::get('success')}}</div>
    @endif
    <table class="table table-bordered">
        <tr>
            <th>#</th>
            <th>Nama Bioskop</th>
            <th>Lokasi</th>
            <th>Aksi</th>
        </tr>
        @foreach ($cinemaTrash as $key => $item)
                <tr>
                    {{-- key -> index array dari 0 --}}
                    <td>{{ $key+1 }}</td>
                    {{-- name dan location dari fillable --}}
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['location'] }}</td>
                    <td class="d-flex justify-content-center">
                        <form action="{{ route('admin.cinemas.restore', $item['id']) }}" method="POST" class="me-2">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-primary">Kembalikan</button>
                        </form>
                        <form action="{{ route('admin.cinemas.delete_permanent', $item['id']) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
    </table>
    </div>

@endsection
