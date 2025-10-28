@extends('template.app')

@section('content')

    <div class="container my-5">
        <form method="POST" action="{{ route('staff.schedules.update', $schedule->id) }}">
            @csrf
            {{-- route method menggunakan patch karena hanya akan mengubah sebagian data : price, hours --}}
            @method('PATCH')
            <div class="mb-3">
                <label for="cinema_id" class="col-form-label">Bioskop:</label>
                {{-- dibuat disable agar tidak dapat di ubah --}}
                <input type="text" name="cinema_id" id="cinema_id" value="{{ $schedule['cinema']['name'] }}" class="form-control" disabled>
            </div>
            <div class="mb-3">
                <label for="movie_id" class="col-form-label">Film:</label>
                <input type="text" name="movie_id" id="movie_id" value="{{ $schedule['movie']['title'] }}" class="form-control" disabled>
            </div>
            <div class="mb-3">
                <label for="price" class="col-form-label">Harga:</label>
                <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ $schedule['price'] }}">
                @error('price')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="hours" class="form-label">Jam Tayang:</label>
                {{-- munculkan sejumlah array --}}
                @foreach ($schedule['hours'] as $index => $hours)
                    <div class="d-flex align-items-center hour-item">
                        <input type="time" name="hours[]" id="hours" class="form-control my-2" value="{{ $hours }}">
                        {{-- this.closest() : mencari element dengan class hour-item yang paling dekat dari element yang di klik, kemudian remove() : hapus --}}
                        @if ($index > 0)
                            <i class="fa-solid fa-circle-xmark text-danger mx-2" style="font-size: 1.5rem; cursor: pointer" onclick="this.closest('.hour-item').remove()"></i>
                        @endif
                    </div>
                @endforeach
                <div id="additionalInput"></div>
                <span class="text-primary my-3" style="cursor: pointer" onclick="addInput()">+Tambah Jam</span>
                @if ($errors->has('hours.*'))
                    <br>
                    <small class="text-danger">{{ $errors->first('hours.*')}}</small>
                @endif
            </div>
            <button type="submit" class="btn btn-primary">Kirim</button>
        </form>
    </div>

@endsection

@push('script')

    <script>
        function addInput() {
            let content = `<div class="d-flex align-items-center hour-additional">
                <input type="time" name="hours[]" id="hours" class="form-control my-2">
                <i class="fa-solid fa-circle-xmark text-danger mx-2" style="font-size: 1.5rem; cursor: pointer" onclick="this.closest('.hour-additional').remove()"></i></div>`
            document.querySelector("#additionalInput").innerHTML += content;
        }
    </script>

@endpush
