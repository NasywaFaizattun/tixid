@extends('template.app')

@section('content')
    <div class="container card my-5 p-4">
        <div class="card-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane"
                        type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Tiket
                        Aktif</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane"
                        type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Tiket
                        Non-Aktif</button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab"
                    tabindex="0">
                    <div class="container mt-4">
                        <h5>Data Tiket Aktif</h5>
                        <div class="d-flex flex-wrap gap-3 mt-5">
                            @foreach ($ticketActive as $active)
                                <div class="w-25">
                                    <div class="d-flex justify-content-end">
                                        <b>{{ $active['schedule']['cinema']['name'] }}</b>
                                    </div>
                                    <hr>
                                    <p><b>{{ $active['schedule']['movie']['title'] }}</b></p>
                                    <p>Tanggal :
                                        {{ \Carbon\Carbon::parse($active['ticketPayment']['booked_date'])->format('d F, Y') }}
                                    </p>
                                    <p>Waktu : {{ \Carbon\Carbon::parse($active['hour'])->format('H:i') }}</p>
                                    <p>Kursi : {{ implode(', ', $active['rows_of_seats']) }}</p>
                                    @php
                                        $price = $active['total_prive'] + $active['service_fee'];
                                    @endphp
                                    <p>Harga : Rp. {{ number_format($price, 0, ',', '.') }}</p>
                                    <a href="{{ route('tickets.export.pdf', $active['id']) }}"
                                        class="btn btn-secondary">Unduh Tiket</a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab"
                    tabindex="0">
                    <div class="container mt-4">
                        <h5>Data Tiket Non-Aktif</h5>
                        <div class="d-flex flex-wrap gap-3 mt-5">
                            @foreach ($ticketNonActive as $nonActive)
                                <div>
                                    <div class="d-flex justify-content-end">
                                        <b>{{ $nonActive['schedule']['cinema']['name'] }}</b>
                                    </div>
                                    <hr>
                                    <p><b></b>{{ $nonActive['schedule']['movie']['title'] }}</b></p>
                                    <p>Tanggal : {{ $nonActive['ticketPayment']['booked_date'] ?? '-' }} </p>
                                    <p>Waktu : {{ \Carbon\Carbon::parse($nonActive['hour'])->format('H:i') }}</p>
                                    <p>Kursi : {{ implode(', ', $nonActive['rows_of_seats']) }}</p>
                                    @php
                                        $price = $nonActive['total_prive'] + $nonActive['service_fee'];
                                    @endphp
                                    <p>Harga : Rp. {{ number_format($price, 0, ',', '.') }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
