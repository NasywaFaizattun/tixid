@extends('template.app')

@section('content')
<div class="container pt-5">
    <div class="w-75 d-block m-auto">
        <div class="d-flex">
            <div style="width: 150px; height: 200px">
                <img src="{{ asset('storage/' . $movie['poster']) }}" alt="" class="w-100">
            </div>
            <div class="ms-5 mt-4">
                <h5>{{ $movie['title'] }}</h5>
                <table>
                    <tr>
                        <td><b class="text-secondary">Genre</b></td>
                        <td class="px-3"></td>
                        <td>: {{ $movie['genre'] }}</td>
                    </tr>
                    <tr>
                        <td><b class="text-secondary">Durasi</b></td>
                        <td class="px-3"></td>
                        <td>: {{ $movie['duration'] }}</td>
                    </tr>
                    <tr>
                        <td><b class="text-secondary">Sutradara</b></td>
                        <td class="px-3"></td>
                        <td>: {{ $movie['director'] }}</td>
                    </tr>
                    <tr>
                        <td><b class="text-secondary">Rating Usia</b></td>
                        <td class="px-3"></td>
                        <td>: <span class="badge badge-danger">{{ $movie['age_rating'] }}+</span></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="w-100 row mt-5">
            <div class="col-6 pe-5">
                <div class="d-flex flex-column justify-content-end align-items-end">
                    <div class="d-flex align-items-center">
                        <h3 class="text-warning me-2">9.5</h3>
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                        <i class="fa-solid fa-star-half-stroke text-warning"></i>
                    </div>
                    <small>61.850 votes</small>
                </div>
            </div>
            <div class="col-6 ps-5" style="border-left: 2px solid #c7c7c7">
                <div class="d-flex align-items-center">
                    <div class="fas fa-heart text-danger me-2"></div>
                    <b>Masukan Watchlist</b>
                </div>
                <small>9.000 Orang</small>
            </div>
        </div>
        <div class="d-flex w-100 bg-light mt-3">
            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle me-3" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Bioskop
                </button>
                <ul class="dropdown-menu">
                    <li><a href="" class="dropdown-item">Bogor</a></li>
                    <li><a href="" class="dropdown-item">Jakarta Timur</a></li>
                    <li><a href="" class="dropdown-item">Jakarta Barat</a></li>
                </ul>
            </div>
            @php
                // ambil query params : request()->get('namaquery')
                // kalau di urk ada ?sortirHarga dan nilainya DESC ganti jadi ASC
                if (request()->get('sortirHarga') == "ASC") {
                    $sortirHarga = 'DESC';
                } elseif (request()->get('sortirHarga') == "DESC") {
                    // kalau di url ada ?sortirHarga dan nilainya DEXC ganti jd ASC
                    $sortirHarga = 'ASC';
                } else {
                    // kalau pertama kali sort, pake ASC
                    $sortirHarga = 'ASC';
                }

                if (request()->get('sortirAlfabet') == "ASC") {
                    $sortirAlfabet = 'DESC';
                } elseif (request()->get('sortirAlfabet') == "DESC") {
                    $sortirAlfabet = 'ASC';
                } else {
                    $sortirAlfabet = 'ASC';
                }
            @endphp
            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Sortir
                </button>
                <ul class="dropdown-menu">
                    {{-- query params di rul : search, sort, limmit form method="GET" atau a href="?" --}}
                    <li><a href="?sortirHarga={{ $sortirHarga }}" class="dropdown-item">Harga</a></li>
                    <li><a href="?sortirAlfabet={{ $sortirAlfabet }}" class="dropdown-item">Alfabet</a></li>
                </ul>
            </div>
        </div>
        <div class="mb-5">
            @foreach ($movie['schedules'] as $schedule)
            <div class="w-100 my-3">
                <div class="d-flex justify-content-between">
                    {{-- kiri --}}
                    <div>
                        <i class="fas fa-building"></i><b class="ms-2">{{ $schedule['cinema']['name'] }}</b><br>
                        <small class="ms-3">{{ $schedule['cinema']['location'] }}</small>
                    </div>
                    {{-- kanan --}}
                    <div>
                        <b>Rp. {{ number_format($schedule['price'], 0, ',', '.') }}</b>
                    </div>
                </div>
                <div class="d-flex gap-3 ps-3 my-2">
                    @foreach ($schedule['hours'] as $hours)
                    <div class="btn btn-outline-secondary">{{$hours}}</div>
                    @endforeach
                </div>
            </div>
            <hr>
            @endforeach
            <hr>
            <div class="w-100 p-2 bg-light text-center fixed-bottom">
                <a href=""><i class="fa-solid fa-ticket"></i> BELI TIKET</a>
            </div>
        </div>
    </div>
</div>
{{-- sore semua besok sekalian uang kas pw kalian juga bayar uang kas ya. jangan lupa sisihin uang nya udah di ingetin ya jadi besok gaada alesan, soalnya dari pada nuggak juga. uang kas 2k seminggu  --}}
@endsection
