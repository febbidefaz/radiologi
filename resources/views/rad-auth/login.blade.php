<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login Radiologi RSA</title>

    <link rel="icon" href="{{ asset('img/logo-rs.png') }}?v=99">

    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">

    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">


    <style>
        body {

            margin: 0;

            background:
                linear-gradient(135deg,
                    #0f172a 0%,
                    #3f66d6 100%);

            font-family:
                'Source Sans Pro',
                sans-serif;
        }


        .login-wrapper {

            min-height: 100vh;

            display: flex;

            justify-content: center;

            align-items: center;

            padding: 20px;
        }


        .login-box {

            width: 420px;

            max-width: 100%;
        }


        .logo {

            text-align: center;

            margin-bottom: 22px;

            color: white;
        }


        .logo img {

            width: 90px;

            height: 90px;

            object-fit: contain;

            margin-bottom: 12px;
        }


        .logo h1 {

            font-size: 30px;

            font-weight: 300;

            margin: 0;
        }


        .logo h1 span {

            font-weight: 700;
        }


        .logo p {

            margin-top: 5px;

            opacity: .85;

            font-size: 15px;
        }


        .card {

            border: 0;

            border-radius: 12px;

            box-shadow:
                0 15px 40px rgba(0, 0, 0, .25);

            overflow: hidden;
        }


        .card-body {

            padding: 32px;
        }


        .subtitle {

            text-align: center;

            color: #475569;

            margin-bottom: 25px;

            font-size: 17px;

            font-weight: 600;
        }


        .form-control {

            height: 48px;

            border-right: 0;
        }


        .input-group-text {

            background: white;

            border-left: 0;

            color: #3f66d6;
        }


        .form-control:focus {

            box-shadow: none;

            border-color: #ced4da;
        }


        .btn-login {

            background: #3f66d6;

            color: white;

            height: 48px;

            font-size: 18px;

            font-weight: 600;

            border: none;

            border-radius: 6px;
        }


        .btn-login:hover {

            background: #3453af;

            color: white;
        }


        .footer-login {

            text-align: center;

            color: #94a3b8;

            font-size: 12px;

            margin-top: 20px;
        }
    </style>

</head>


<body>


    <div class="login-wrapper">


        <div class="login-box">


            <div class="logo">
                <img src="{{ asset('img/logo-rs.png') }}" alt="RSA">

                <h1>
                    <span>RAD</span> RSA
                </h1>

                <p>
                    Sistem Informasi Radiologi
                </p>

            </div>


            <div class="card">


                <div class="card-body">


                    <div class="subtitle">

                        <i class="fas fa-flask mr-1"></i>

                        Login Radiologi

                    </div>


                    @if (session('error'))
                        <div class="alert alert-danger">

                            <i class="fas fa-exclamation-circle mr-1"></i>

                            {{ session('error') }}

                        </div>
                    @endif


                    @if ($errors->any())
                        <div class="alert alert-danger">

                            {{ $errors->first() }}

                        </div>
                    @endif


                    <form method="POST" action="{{ route('rad.login.post') }}">

                        @csrf


                        <div class="input-group mb-3">


                            <input type="text" name="Username" class="form-control" placeholder="Username"
                                value="{{ old('Username') }}" autocomplete="username" required autofocus>


                            <div class="input-group-append">

                                <div class="input-group-text">

                                    <span class="fas fa-user"></span>

                                </div>

                            </div>

                        </div>


                        <div class="input-group mb-4">


                            <input type="password" name="Password" id="radPassword" class="form-control"
                                placeholder="Password" autocomplete="current-password" required>


                            <div class="input-group-append">

                                <button type="button" class="input-group-text border-left-0"
                                    onclick="toggleRadPassword()">

                                    <span id="iconPassword" class="fas fa-eye"></span>

                                </button>

                            </div>

                        </div>


                        <button type="submit" class="btn btn-login btn-block">

                            <i class="fas fa-sign-in-alt mr-1"></i>

                            Login

                        </button>


                    </form>


                </div>

            </div>


            <div class="footer-login">

                RS 'Aisyiyah Bojonegoro

            </div>


        </div>

    </div>


    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>

    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>


    <script>
        function toggleradPassword() {
            const input =
                document.getElementById(
                    'radPassword'
                );

            const icon =
                document.getElementById(
                    'iconPassword'
                );


            if (input.type === 'password') {

                input.type = 'text';

                icon.classList.remove(
                    'fa-eye'
                );

                icon.classList.add(
                    'fa-eye-slash'
                );

            } else {

                input.type = 'password';

                icon.classList.remove(
                    'fa-eye-slash'
                );

                icon.classList.add(
                    'fa-eye'
                );
            }
        }
    </script>


</body>

</html>
