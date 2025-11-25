@extends('template.app')

@section('content')
    <div class="container">
        <h5 class="my-3">Grafik Pembelian</h5>
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }} <b>Selamat Datang {{ Auth::user()->name }}</b>
            </div>
        @endif
        <div class="row mt-5">
            <div class="col-6">
                <canvas id="chartBar"></canvas>
            </div>
            <div class="col-6">
                <canvas id="chartPie"></canvas>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        let labels = null;
        let data = null;
        let dataPie = null;

        $(function() {
            $.ajax({
                url: "{{ route('admin.tickets.chart') }}",
                method: "GET",
                success: function(response) {
                    labels = response.labels;
                    data = response.data;
                    chartBar()
                },
                error: function(err) {
                    alert('Gagal mengambil data untuk grafik!');
                }
            });

            $.ajax({
                url: "{{ route('admin.movies.chart') }}",
                method: "GET",
                success: function(response) {
                    dataPie = response.data;
                    chartPie()
                },
                error: function(err) {
                    alert('Gagal mengambil data untuk grafik!');
                }
            })
        });

        const ctx = document.getElementById('chartBar');
        function chartBar() {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Penjualan Ticket Bulan Ini',
                        data: data,
                        borderwidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

            const ctx2 = document.getElementById('chartPie');
            function chartPie() {
                new Chart(ctx2, {
                    type: 'pie',
                    data: {
                        labels: [
                            'Film Aktif',
                            'Film Non-Aktif',
                        ],
                        datasets: [{
                            label: 'Perbandingan Film Aktif dan Non-Aktif',
                            data: dataPie,
                            backgroundColor: [
                                'rgb(255, 99, 132)',
                                'rgb(54, 162, 235)',
                            ],
                            hoverOffset: 4
                        }]
                    }
                });
            }
    </script>
@endpush
