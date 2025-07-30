<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UNIENVIO|AGBC</title>
    <link rel="icon" type="image/png" href="{{ asset('images/AGBClogo.png') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            background: #f1f7fc;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-clean {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-clean form {
            max-width: 360px;
            width: 100%;
            background-color: #ffffff;
            padding: 40px;
            border-radius: 4px;
            color: #505e6c;
            box-shadow: 1px 1px 10px rgba(0, 0, 0, 0.15);
        }

        .login-clean .illustration {
            text-align: center;
            padding-bottom: 20px;
            font-size: 100px;
            color: rgb(244, 71, 107);
        }

        .login-clean form .form-control {
            background: #f7f9fc;
            border: none;
            border-bottom: 1px solid #dfe7f1;
            border-radius: 0;
            box-shadow: none;
            outline: none;
            text-indent: 8px;
            height: 42px;
        }

        .login-clean form .btn-primary {
            background: #f4476b;
            border: none;
            border-radius: 4px;
            padding: 11px;
            margin-top: 26px;
        }

        .login-clean form .btn-primary:hover {
            background: #eb3b60;
        }

        .login-clean form .forgot {
            display: block;
            text-align: center;
            font-size: 12px;
            color: #6f7a85;
            opacity: 0.9;
            text-decoration: none;
        }

        .login-clean form .forgot:hover {
            opacity: 1;
        }

        .error-message {
            font-size: 0.9em;
            color: red;
            margin-top: 5px;
        }

        .status-message {
            text-align: center;
            color: green;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="login-clean">
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <h2 class="sr-only">Login Form</h2>
            <!-- Reemplazo del icono por imagen -->
            <div class="illustration text-center">
                <img src="{{ asset('images/AGBClogo.png') }}" alt="Logo" class="img-fluid mb-3"
                    style="max-width: 120px;">
                <h5 style="font-weight: bold; font-size: 14px; color: #333;">
                    SISTEMA DE GESTIÓN DE ENVÍOS DE CORRESPONDENCIA AGRUPADA<br>
                    <span style="color: #f4476b;">“UNIENVÍO”</span>
                </h5>
            </div>



            <!-- Mensaje de sesión -->
            @if (session('status'))
                <div class="status-message">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Email -->
            <div class="form-group">
                <input class="form-control" type="email" name="email" value="{{ old('email') }}"
                    placeholder="Email" required autofocus>
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group">
                <input class="form-control" type="password" name="password" placeholder="Password" required>
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="form-group form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">Recuérdame</label>
            </div>

            <!-- Submit -->
            <div class="form-group">
                <button class="btn btn-primary btn-block" type="submit">Iniciar sesión</button>
            </div>

            <!-- Forgot Password -->
            {{-- @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="forgot">¿Olvidaste tu contraseña?</a>
            @endif --}}
        </form>
    </div>

    <!-- Scripts opcionales -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>
</body>

</html>
