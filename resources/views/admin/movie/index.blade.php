@extends('template.app')
@section('content')
    <div class="container my-5">
        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.movies.trash') }}" class="btn btn-secondary me-2">Data Sampah</a>
            <a href="{{ route('admin.movies.export') }}" class="btn btn-primary me-2">Export</a>
            <a href="{{ route('admin.movies.create') }}" class="btn btn-success">Tambah Data</a>
        </div>
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif
        @if (Session::get('error'))
            <div class="alert alert-danger">{{ Session::get('error') }}</div>
        @endif
        <h5 class="mb-3">Data Film</h5>
        <table class="table table-bordered" id="tableMovie">
            <thead>
            <tr class="text-center">
                <th>#</th>
                <th>Poster</th>
                <th>Judul Film</th>
                <th>Status Aktif</th>
                <th>Aksi</th>
            </tr>
            </thead>
        </table>

        <!-- Modal -->
        <div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="modalDetail" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalDetail">Detail Film</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modalDetailBody">
                        ...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(function() {
            $("#tableMovie").DataTable({
                processing: true, // tanda load pas lagi proses data
                serverSide: true, // data di proses dibelakang (controller)
                ajax: "{{ route('admin.movies.datatables') }}", // memanggil route
                columns: [
                    // urutan <td>
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'imgPoster',
                        name: 'imgPoster',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'title',
                        name: 'title',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'activeBadge',
                        name: 'activeBadge',
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

        function showModal(item) {
            let image = "{{ asset('storage') }}" + "/" + item.poster;
            //menyiapkan konten HTML yang akan ditambahkan
            // backtip (diatas tab) : menulis string lebih dari 1 baris
            let content = `
            <img src="${image}" width="120" class="d-block mx-auto my-3">
            <ul>
                <li>Judul : ${item.title}</li>
                <li>Duration : ${item.duration}</li>
                <li>Genre : ${item.genre}</li>
                <li>Sutradara : ${item.director}</li>
                <li>Usia Minimal : <span class="badge badge-danger"> ${item.age_rating} </span></li>
                <li>Sinopsis : ${item.description}</li>
            </ul>

            `;
            //document.querySelector() mengambil element HTML, # berdasarkan id
            let modalDetailBody = document.querySelector('#modalDetailBody');
            //InnerHTML simpan konten html yang dibuat diatas ke element HTML id="modalDetailBody"
            modalDetailBody.innerHTML = content;
            let modalDetail = document.querySelector('#modalDetail');
            //Munculkan modal boostrap, modal yang id nya id="modalDetail"
            new bootstrap.Modal(modalDetail).show();
        }
    </script>
@endpush
