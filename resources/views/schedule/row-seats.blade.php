@extends('template.app')

@section('content')
    <div class="container card my-5 p-4" style="margin-bottom: 20% !important">
        <div class="card-body">
            <b>{{ $schedule['cinema']['name'] }}</b>
            {{-- now() ambil tgl hari ini, format: F nama bulan --}}
            <br><b>{{ now()->format('d F Y') }} - {{ $hour }}</b>
            <div class="alert alert-secondary">
                <i class="fa-solid fa-info text-danger"></i> Anak berusia 2 tahun wajib membeli tiket.
            </div>
            <div class="w-50 d-block mx-auto my-4">
                <div class="row">
                    <div class="col-4 d-flex">
                        <div style="width: 20px; height: 2-px; background: blue; margin-right: 5px;">
                        </div>Kursi Dipilih
                    </div>
                    <div class="col-4 d-flex">
                        <div style="width: 20px; height: 2-px; background: #112646; margin-right: 5px;">
                        </div>Kursi Tersedia
                    </div>
                    <div class="col-4 d-flex">
                        <div style="width: 20px; height: 2-px; background: #eaeaea; margin-right: 5px;">
                        </div>Kursi Terjual
                    </div>
                </div>
            </div>

            @php
                // array untuk looping, range() : membuat rentang tertentu menjadi array
                $rows = range('A', 'H');
                $cols = range(1, 18);
            @endphp
            {{-- looping pertama bikin baris kebawah A-H --}}
            @foreach ($rows as $row)
                {{-- untuk loop 1-18 kesamping dibungkus d-flex --}}
                <div class="d-flex justify-content-center align-items-center">
                    @foreach ($cols as $col)
                        @if ($col == 7)
                            {{-- memberi kotak kosong untuk jarak kursi 6 dan 7 (jalur jalan) --}}
                            <div style="width: 50px;"></div>
                        @endif
                        <div style="width: 45px; height:45px; text-align: center; font-weight: bold; color: white; padding-top: 10px; cursor: pointer; background: #112646; margin: 5px; border-radius: 8px;"
                            onclick="selectSeat('{{ $schedule->price }}', '{{ $row }}', '{{ $col }}', this)">
                            {{ $row }}-{{ $col }}</div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>

    <div class="fixed-bottom">
        <div class="p-4 bg-light text-center w-100"><b>LAYAR BIOSKOP</b></div>
        <div class="row w-100 bg-light">
            <div class="col-6 py-3 text-center" style="border: 1px solid grey">
                <h5>Total Harga</h5>
                <h5 id="totalPrice">Rp. -</h5>
            </div>
            <div class="col-6 py-3 text-center" style="border: 1px solid grey">
                <h5>Kursi Dipilih</h5>
                <h5 id="seats">-</h5>
            </div>
        </div>
        {{-- input:hidden nyimpen nilai yg diperlukan JS untuk membuat data. namun di tampilan di sembunyikan --}}
        <input type="hidden" id="user_id" value="{{ Auth::user()->id }}">
        <input type="hidden" id="schedule_id" value="{{ $schedule->id }}">
        <input type="hidden" id="date" value="{{ now() }}">
        <input type="hidden" id="hour" value="{{ $hour }}">

        <div class="w-100 bg-light p-2 text-center" id="btnOrder"><b>RINGKASAN ORDER</b></div>
    </div>
@endsection

@push('script')
    <script>
        let seats = [];
        let totalPrice = 0;

        function selectSeat(price, row, col, element) {
            // buat format nomor kursi : A-10
            let seat = row + "-" + col;
            // cek array seats apakah kursi ini sudah ada di array atau blm (uda pernah di klik/blm)
            // indexOf() mencari item di array dan mengembalikan nilai index itemnya
            let indexSeat = seats.indexOf(seat);
            // jika ada item maka index array bernilai 0-dst kalau gaada -1
            if (indexSeat == -1) {
                // kalau kuirsi tsb blm ada di array maka tambahkan dan warna biru
                seats.push(seat); // push : menambahkan item array
                element.style.background = 'blue';
            } else {
                // kalau kursi ada di array artinya klik kai ini untuk hapus
                seats.splice(indexSeat, 1); // splice : menghapus item array sesuai index yang di berikan sebanyak 1
                element.style.background = '#112646';
            }

            totalPrice = price * seats.length; // length : count php, menghitung isi array
            let totalPriceElement = document.querySelector("#totalPrice");
            totalPriceElement.innerText = totalPrice;

            let seatsElement = document.querySelector("#seats");
            // join(', ') : mengubah array jd string, di pisahkan tanda koma
            seatsElement.innerText = seats.join(', ');

            let btnOrder = document.querySelector("#btnOrder");
            if (seats.length > 0) {
                btnOrder.classList.remove('bg-light');
                btnOrder.style.background = '#112646';
                btnOrder.style.color = 'white';
                btnOrder.style.cursor = 'pointer';
                // kalau di klik lakukan proses pembuatan data tiket
                btnOrder.onclick = createTicket;
            } else {
                // classList : mengakses class HTML, add tambah class remove hapus class
                btnOrder.classList.add('bg-light');
                btnOrder.style.background = '';
                btnOrder.style.color = '';
                btnOrder.style.cursor = '';
                btnOrder.onclick = null;
            }
        }

        function createTicket() {
            // ajax (asynchronus javascript daan xml) : mengakses data di database (BE) lewat .JS diguankan jquery ($)
            $.ajax({
                url: "{{ route('tickets.store') }}", // routing proses data
                method: "POST", // http method
                data: {
                _token: "{{ csrf_token() }}", // token csrf
                // fillable : value, data yang akan dikirim ke BE
                user_id: $("#user_id").val(), // ambil value dr input id="user_id"
                schedule_id: $("#schedule_id").val(),
                date: $("#date").val(),
                hour: $("#hour").val(),
                rows_of_seats: seats,
                quantity: seats.length,
                total_price: totalPrice,
                service_fee: 4000 * seats.length
                },
                success: function(response) {
                    // kalau berhasil mau ngapain
                    // window.location.href : redirect halaman lewat js
                    let ticketId = response.data.id;
                    window.location.href = `/tickets/${ticketId}/order`;
                },
                error: function(message) { // kalau error mau ngapain
                    alert('Gagal membuat data tiket!')
                }
            });
        }
    </script>
@endpush
