@extends('template.app')

@section('content')

    <div class="container my-3">
        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    <h3 class="my-3">Data Sampah Data Petugas</h3>
    @if (Session::get('success'))
        <div class="alert alert-success">{{ Session::get('success')}}</div>
    @endif
    <table class="table table-bordered">
        <tr>
                <th>#</th>
                <th>Nama Lengkap</th>
                <th>Email</th>
                <th>Role</th>
                <th>Aksi</th>
        </tr>
        @foreach ($userTrash as $index => $item)
                <tr>
                    <td>{{ $index+1 }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->email }}</td>
                    <td>
                        @if ($item->role == 'admin')
                            <span class="badge badge-primary">{{ $item['role'] }}</span>
                        @else
                            <span class="badge badge-success">{{ $item['role'] }}</span>
                        @endif
                    </td>
                    <td class="d-flex">
                        <form action="{{ route('admin.users.restore', $item['id']) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-primary">Kembalikan</button>
                        </form>
                        <form action="{{ route('admin.users.delete_permanent', $item['id']) }}" method="POST">
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
