@extends('template.app')

@section('content')
    <div class="container align-items-center w-75 d-block mx-auto my-5 p-4 align-items-center">
        <form method="POST" action="{{ route('staff.promos.store') }}" class="card p-4 my-4">
            <h5 class="text-center my-3">Buat Data Promo</h5>
            @csrf
            @if (Session::get('error'))
                <div class="alert alert-danger">
                    {{ Session::get('error') }}
                </div>
            @endif
            <div class="mb-3">
                <label for="name" class="form-label">Kode Promo</label>
                <input type="text" class="form-control @error('promo_code') is-invalid @enderror" id="promo_code"
                    name="promo_code">
                @error('promo_code')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Tipe Promo</label>
                <select name="type" class="form-control @error('type') is-invalid @enderror" id="type">
                    <option value="percent">%</option>
                    <option value="rupiah">Rupiah</option>
                </select>
                @error('type')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="discount" class="form-label">Jumlah Potongan</label>
                <input type="number" class="form-control @error('discount') is-invalid @enderror" id="discount"
                    name="discount">
                <small class="form-text text-muted" id="discountHelp">Isi sesuai tipe yang dipilih (% atau Rupiah)</small>
                @error('discount')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary w-100">Buat</button>
        </form>
    </div>
@endsection
