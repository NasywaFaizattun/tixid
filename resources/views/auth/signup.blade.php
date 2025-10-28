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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.1.0/mdb.min.css" rel="stylesheet" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <form class="w-50 d-block mx-auto my-5" method="POST" action="{{ route('signup.register')}}">
        {{-- csrf: generate token yang menjadi syarat bagi FE menigrim data ke server/ BE --}}
        @csrf
        <!-- 2 column grid layout with text inputs for the first and last names -->

        <div class="row mb-4">
            <div class="col">
                <div data-mdb-input-init class="form-outline">
                    {{-- name="" : memberikan identitas data agar data yang di input bisa di akses controller, namanya snack case bahasa inggris --}}
                    <input type="text" id="form3Example1" class="form-control @error('first_name') is-invalid
                    @enderror" name="first_name" value="{{ old('first_name') }}"/>
                    <label class="form-label" for="form3Example1">First name</label>
                </div>
                {{-- memunculkan tulisan error validasi @error('nama_input') --}}
                @error('first_name')
                    <small class="text-danger">*{{ $message }}</small>
                @enderror
            </div>
            <div data-mdb-input-init class="col">
                <div class="form-outline">
                    <input type="text" id="form3Example2" class="form-control @error('last_name') is-invalid
                    @enderror" name="last_name" value="{{ old('last_name') }}" />
                    <label class="form-label" for="form3Example2">Last name</label>
                </div>
                @error('last_name')
                    <small class="text-danger">*{{ $message }}</small>
                @enderror
            </div>
        </div>

        <!-- Email input -->
            @error('email')
                <small class="text-danger">*{{ $message }}</small>
            @enderror
        <div data-mdb-input-init class="form-outline mb-4">
            <input type="email" id="form3Example3" class="form-control @error('email') is-invalid
                    @enderror" name="email" value="{{ old('email') }}" />
            <label class="form-label" for="form3Example3">Email address</label>
        </div>

        <!-- Password input -->
            @error('password')
                <small class="text-danger">*{{ $message }}</small>
            @enderror
        <div data-mdb-input-init class="form-outline mb-4">
            {{-- old('name_input') : mengambil data inputan sebelumnya utnuk diisi kemabali ke input --}}
            <input type="password" id="form3Example4" class="form-control @error('password') is-invalid
                    @enderror" name="password" value="{{ old('password') }}" />
            <label class="form-label" for="form3Example4">Password</label>
        </div>
        </div>

        {{-- Submit button --}}
        <button data-mdb-input-init type="submit" class="btn btn-primary btn-block">Sign Up</button>
        <div class="text-center mt-3">
            <a href="{{ route('home') }}">Kembali</a>
        </div>
    </form>

    <!-- MDB -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.6.0/mdb.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"
        integrity="sha384-7qAoOXltbVP82dhxHAUje59V5r2YsVfBafyUDxEdApLPmcdhBPg1DKg1ERo0BZlK" crossorigin="anonymous">
    </script>
</body>

</html>
