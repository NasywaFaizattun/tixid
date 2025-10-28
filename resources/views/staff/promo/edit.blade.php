@extends('template.app')

@section('content')
    <div class="container align-items-center w-75 d-block mx-auto my-5 p-4 align-items-center">
        <form method="POST" action="{{ route('staff.promos.update', $promo->id) }}" class="card p-4 my-4">
            @csrf
            @if (Session::get('error'))
                <div class="alert alert-danger">
                    {{ Session::get('error') }}
                </div>
            @endif
            @method('PUT')
            <h5 class="text-center my-3">Edit Data Promo</h5>
            <div class="mb-3">
                <label for="promo_code" class="form-label">Kode Promo</label>
                <input type="text" class="form-control @error('promo_code') is-invalid @enderror" id="promo_code"
                    name="promo_code" value="{{ old('promo_code', $promo->promo_code) }}">
                @error('promo_code')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="type" class="form-label">Tipe Promo</label>
                <select name="type" class="form-control @error('type') is-invalid @enderror" id="type">
                    <option value="percent" {{ $promo->type == 'percent' ? 'selected' : '' }}>%</option>
                    <option value="rupiah" {{ $promo->type == 'rupiah' ? 'selected' : '' }}>Rupiah</option>
                </select>
                @error('type')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="discount" class="form-label">Jumlah Potongan</label>
                <input type="number" class="form-control @error('discount') is-invalid @enderror" id="discount"
                    name="discount" value="{{ old('discount', $promo->discount) }}">
                <small class="form-text text-muted">Isi sesuai tipe yang dipilih (% atau Rupiah)</small>
                @error('discount')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-100">Kirim</button>
        </form>
    </div>
@endsection
