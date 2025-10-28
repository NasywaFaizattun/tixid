@extends('template.app')

@section('content')
    <div class="container mt-3">
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif
        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.users.trash') }}" class="btn btn-secondary me-2">Data Sampah</a>
            <a href="{{ route('admin.users.export') }}" class="btn btn-primary me-2">Export</a>
            <a href="{{ route('admin.users.create') }}" class="btn btn-success">Tambah Data</a>
        </div>
        <h5 class="mt-3 my-3">Data Pengguna (Admin & Staff)</h5>
        <table class="table table-bordered" id="tableUser">
            <thead>
            <tr>
                <th></th>
                <th>Nama Lengkap</th>
                <th>Email</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
            </thead>
        </table>
    </div>

    <style>

    </style>
@endsection

@push('script')
<script>
    $(function() {
            $("#tableUser").DataTable({
                processing: true, // tanda load pas lagi proses data
                serverSide: true, // data di proses dibelakang (controller)
                ajax: "{{ route('admin.users.datatables') }}", // memanggil route
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'email',
                        name: 'email',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'role',
                        name: 'role',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'btnActions',
                        name: 'btnActions',
                        orderable: false,
                        searchable: false
                    },
                ]
            })
        })
</script>

@endpush
