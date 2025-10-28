@extends('template.app')

@section('content')

    <div class="container my-3">
        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.movies.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    <h3 class="my-3">Data Sampah Film</h3>
    @if (Session::get('success'))
        <div class="alert alert-success">{{ Session::get('success')}}</div>
    @endif
    <table class="table table-bordered">
        <tr class="text-center">
                <th>#</th>
                <th>Poster</th>
                <th>Judul Film</th>
                <th>Status Aktif</th>
                <th>Aksi</th>
        </tr>
        @foreach ($movieTrash as $key => $item)
                <tr class="text-center">
                    <th>{{ $key + 1 }}</th>
                    {{-- Asset() : Memanggil folder public --}}
                    <th>
                        <img src="{{ asset('storage/' . $item['poster']) }}" width="120" alt="">
                    </th>
                    <th>{{ $item->title }}</th>
                    <th>
                        @if ($item['actived'] == 1)
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-danger">Non-Aktif</span>
                        @endif
                    </th>
                    <th class="d-flex justify-content-center">
                        <form action="{{ route('admin.movies.restore', $item['id']) }}" method="POST" class="me-2">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-primary me-2">Kembalikan</button>
                        </form>
                        <form action="{{ route('admin.movies.delete_permanent', $item['id']) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger me-2">Hapus</button>
                        </form>
                    </th>
                </tr>
         @endforeach
    </table>
    </div>

@endsection
