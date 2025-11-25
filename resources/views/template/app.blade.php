<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon"
        href="https://play-lh.googleusercontent.com/FcRZx_UEXN2uc7uKM5EKGn7Jmb65c8VVELlmligxdfUcjKKIpzFX0SHXFePllD2g4ik"
        type="image/x-icon">
    <title>TIXid</title>
    {{-- Jquery (prioritas) --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.1.0/mdb.min.css" rel="stylesheet" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- datatables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <!-- Container wrapper -->
        <div class="container">
            <!-- Navbar brand -->
            <a class="navbar-brand me-2" href="https://mdbgo.com/">
                <img src="https://asset.tix.id/wp-content/uploads/2021/10/TIXID_logo_blue-300x82.png"
                    height="16" alt="MDB Logo" loading="lazy" style="margin-top: -1px;" />
            </a>

            <!-- Toggle button -->
            <button data-mdb-collapse-init class="navbar-toggler" type="button" data-mdb-target="#navbarButtonsExample"
                aria-controls="navbarButtonsExample" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Collapsible wrapper -->
            <div class="collapse navbar-collapse" id="navbarButtonsExample">
                <!-- Left links -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    @if (Auth::check() && Auth::user()->role == 'admin')
                        <li class="nav-item">
                            <a class="nav-link" href="#">Dashboard</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a data-mdb-dropdown-init class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" aria-expanded="false">Data Master </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.cinemas.index') }}">Data Bioskop</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.movies.index') }}">Data Film</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.users.index') }}">Data Petugas</a>
                            </li>
                        </ul>
                        </li>
                    @elseif (Auth::check() && Auth::user()->role == 'staff')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('staff.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('staff.schedules.index')}}">Jadwal Tayang</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('staff.promos.index') }}">Promo</a>
                        </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home')}}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cinemas.list') }}">Bioskop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('tickets.index') }}">Tiket</a>
                    </li>
                    @endif
                </ul>
                <!-- Left links -->

                <div class="d-flex align-items-center">
                    {{-- Auth::check() -> ngecek udh log in/belum --}}
                    @if (Auth::check())
                        <a href="{{ route('logout') }}" class="btn btn-danger">LOGOUT</a>
                    @else
                        <a href="{{ route('login') }}" type="button" class="btn btn-link px-3 me-2">
                            LogIn
                        </a>
                        <a href="{{ route('signup')}}" type="button" class="btn btn-primary me-3">
                            Sign Up
                        </a>
                    @endif
                </div>
            </div>
            <!-- Collapsible wrapper -->
        </div>
        <!-- Container wrapper -->
    </nav>
    <!-- Navbar -->

    {{-- konten dinamis --}}
    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"
        integrity="sha384-7qAoOXltbVP82dhxHAUje59V5r2YsVfBafyUDxEdApLPmcdhBPg1DKg1ERo0BZlK" crossorigin="anonymous">
    </script>
    <!-- MDB -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.1.0/mdb.umd.min.js"></script>
    {{-- datatables --}}
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
    {{-- chartJs --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- konten dinamis JS --}}
    @stack('script')
</body>

</html>
