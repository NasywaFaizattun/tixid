@extends('template.app')

@section('content')
    <div class="container my-5">
        <h5 class="mb-5">Seluruh Film Sedang Tayang</h5>
        {{-- form utnuk mencari/search, gunakan GET. action ke route dan halaman yang sama jadi di kosongkan --}}
        <form action="" method="GET">
            @csrf
            <div class="row">
                <div class="col-10">
                    <input type="text" name="search_movie" placeholder="Cari judul film..." class="form-control">
                </div>
                <div class="col-2">
                    <button type="submit" class="btn btn-primary">Cari</button>
                </div>
            </div>
        </form>
        <div class="d-flex justify-content-center gap-2 my-3">
            @foreach ($movies as $movie)
                <div class="card" style="width: 13rem">
                    <img src="{{ asset('storage/' . $movie->poster) }}" style="height: 300px; object-fit: cover;" class="card-img-top" alt="{{ $movie->title }}" />
                <div class="card-body" style="padding: 0 !important">
                    <p class="card-text text-center bg-primary py-2">
                        <a href="{{ route('schedule.detail', $movie['id']) }}" class="text-warning">Beli Tiket</a>
                    </p>
                </div>
                </div>
            @endforeach
        </div>

    </div>
@endsection
