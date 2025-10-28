@extends('template.app')

@section('content')

    <div class="container my-3">
        <div class="d-flex justify-content-end">
            <a href="{{ route('staff.schedules.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    <h3 class="my-3">Data Sampah Jadwal Tayang</h3>
    @if (Session::get('success'))
        <div class="alert alert-success">{{ Session::get('success')}}</div>
    @endif
    <table class="table table-bordered">
        <tr>
            <th>#</th>
            <th>Nama Bioskop</th>
            <th>Judul Film</th>
            <th>Aksi</th>
        </tr>
        @foreach ($scheduleTrash as $key => $schedule)
            <tr>
                <td>{{ $key+1}}</td>
                {{-- mengambil relasi $item['namarelasi']['data'] --}}
                <td>{{ $schedule['cinema']['name'] ?? ''}}</td>
                <td>{{ $schedule['movie']['title'] ?? '' }}</td>
                <td class="d-flex justify-content-center">
                    <form action="{{ route('staff.schedules.restore', $schedule->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button class="btn btn-primary ms-2">Kembalikan</button>
                    </form>
                    <form action="{{ route('staff.schedules.delete_permanent', $schedule->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger ms-2">Hapus Permanen</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
    </div>

@endsection
