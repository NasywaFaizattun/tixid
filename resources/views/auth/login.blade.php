<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="https://play-lh.googleusercontent.com/FcRZx_UEXN2uc7uKM5EKGn7Jmb65c8VVELlmligxdfUcjKKIpzFX0SHXFePllD2g4ik" type="image/x-icon">
    <title>TIXid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.1.0/mdb.min.css" rel="stylesheet" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

    {{-- mengecek jika ada with('success') dari controllernya, jika ada munculkan didalam alert success --}}
    @if (Session::get('success'))
        <div class="alert alert-success">{{ Session::get('success') }}</div>
    @endif
    @if (Session::get('error'))
        <div class="alert alert-danger">{{ Session::get('error') }}</div>
    @endif
    <form class="w-50 d-block mx-auto my-5" method="POST" action="{{ route('login.auth') }}">
        @csrf
        {{-- Email input --}}
        @error('email')
            <small class="text-danger">*{{ $message }}</small>
        @enderror
        <div data-mdb-input-init class="form-outline mb-4 ">
            <input type="email" id="form1Example1" class="form-control @error('email') is-invalid @enderror"    name="email">
            <label for="form1Example1" class="form-label">Email</label>
        </div>

        {{-- Password Input --}}
        @error('password')
            <small class="text-danger">*{{ $message }}</small>
        @enderror
        <div data-mdb-input-init class="form-outline mb-4 ">
            <input type="password" id="form1Example2" class="form-control @error('password') is-invalid @enderror" name="password">
            <label for="form1Example2" class="form-label">Password</label>
        </div>

        {{-- Submit button --}}
        <button data-mdb-input-init type="submit" class="btn btn-primary btn-block">Login</button>
        <div class="text-center mt-3">
            <a href="{{ route('home') }}">Kembali</a>
        </div>
    </form>

    <!-- MDB -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.6.0/mdb.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" integrity="sha384-7qAoOXltbVP82dhxHAUje59V5r2YsVfBafyUDxEdApLPmcdhBPg1DKg1ERo0BZlK" crossorigin="anonymous"></script>
</body>

</html>
