@extends('template.app')

@section('content')
    <div class="container">
        <h5 class="my-3">Grafik Pembelian</h5>
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }} <b>Selamat Datang {{ Auth::user()->name  }}</b></div>
        @endif
    </div>
@endsection
