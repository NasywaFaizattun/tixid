<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tiket</title>
    <style>
        .ticket-wrapper {
            margin-left: 20%;
            width: 50%;
            margin-top: 5p%;
        }
        .ticket {
            width: 100%;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="ticket-wrapper">
        @foreach ($ticket['rows_of_seats'] as $kursi)
            <div class="ticket">
                <div style="text-align: right">
                    <b>{{ $ticket['schedule']['cinema']['name'] }}</b>
                </div>
                <hr>
                {{-- toArray : relasi dua kata jadi pake snack case(_) --}}
                <p>Tanggal : {{ \Carbon\Carbon::parse($ticket['ticket_payment']['booked_date'])->format('d F, Y')}}</p>
                <p>Waktu : {{ \Carbon\Carbon::parse($ticket['hour'])->format('H:i') }}</p>
                <p>Kursi : {{ $kursi }}</p>
                <p>Harga : Rp. {{ number_format($ticket['schedule']['price'], 0, ',', '.') }}</p>
            </div>
            <hr style="margin-bottom: 20px">
        @endforeach
    </div>
</body>
</html>
