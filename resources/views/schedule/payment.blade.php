@extends('template.app')

@section('content')
    <div class="container car my-5 p-4">
        <div class="card-body">
            <h5 class="text-center">Selesaikan Pembayaran</h5>
            <img src="{{ asset('storage/' . $ticket['ticketPayment']['qrcode']) }}" alt="" class="d-block mx-auto">
            <div class="w-25 d-block mx-auto mb-4">
                <table class="w-100">
                    <tr>
                        <td>2 Tiket</td>
                        <td><b>{{ implode(', ', $ticket['rows_of_seats']) }}</b></td>
                    </tr>
                    <tr>
                        <td>Kursi Regular</td>
                        <td><b>Rp. {{ number_format($ticket['schedule']['price'], 0, ',', '.') }}<span class="text-secondary">x{{ $ticket['quantity'] }}</span></b></td>
                    </tr>
                    <tr>
                        <td>Biaya Layanan</td>
                        <td><b>Rp. 4.000<span class="text-secondary">x{{ $ticket['quantity'] }}</span></b></td>
                    </tr>
                    <tr>
                        <td>Promo</td>
                        @php
                            if ($ticket['promo']) {
                                $promo = $ticket['promo']['type'] == 'percent' ?
                                $ticket['promo']['discount'] . '%' : 'Rp. ' . number_format($ticket['promo']['discount'], 0, ',', '.');
                            } else {
                                $promo ='Rp. 0';
                            }
                        @endphp
                        <td><b>{{ $promo }}</b></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
@endsection
