@extends('template.app')

@section('content')

    <div class="container my-3">
        <div class="d-flex justify-content-end">
            <a href="{{ route('staff.promos.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    <h3 class="my-3">Data Sampah Promo</h3>
    @if (Session::get('success'))
        <div class="alert alert-success">{{ Session::get('success')}}</div>
    @endif
    <table class="table table-bordered">
        <tr>
            <th>#</th>
            <th>Kode Promo</th>
            <th>Total Potongan</th>
            <th>Aksi</th>
        </tr>
         @foreach ($promoTrash as $key => $item)
                <tr class="text-center">
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item['promo_code'] }}</td>
                    <td>
                        @if ($item['type'] == 'percent')
                            <p>{{ $item['discount'] }}%</p>
                        @else
                            <p>Rp {{ number_format($item['discount'], 0, ',', '.') }}</p>
                        @endif
                    </td>
                    <td class="d-flex justify-content-center">
                        <form action="{{ route('staff.promos.restore', $item->id) }}" method="POST" class="me-2">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-primary">Kembalikan</button>
                        </form>
                        <form action="{{ route('staff.promos.delete_permanent', $item->id) }}" method="POST">
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
