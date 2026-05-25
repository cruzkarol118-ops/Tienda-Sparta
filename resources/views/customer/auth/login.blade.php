<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/bootstrap-icons/bootstrap-icons.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/app.css')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Nunito', sans-serif;
            background: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .auth-container {
            width: 100%;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .auth-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 440px;
            padding: 50px 40px;
            position: relative;
        }
        .auth-card .logo {
            text-align: center;
            margin-bottom: 10px;
        }
        .auth-card .logo h1 {
            font-size: 2rem;
            font-weight: 800;
            color: #000;
            letter-spacing: -0.5px;
        }
        .auth-card .logo h1 span { color: #D4AF37; }
        .auth-card .subtitle {
            text-align: center;
            color: #6c757d;
            font-size: 0.95rem;
            margin-bottom: 35px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #333;
            margin-bottom: 6px;
            display: block;
        }
        .input-wrapper {
            position: relative;
        }
        .input-wrapper .form-control {
            height: 50px;
            border-radius: 12px;
            border: 2px solid #e9ecef;
            padding-left: 45px;
            font-size: 0.95rem;
            transition: border-color 0.3s;
        }
        .input-wrapper .form-control:focus {
            border-color: #D4AF37;
            box-shadow: 0 0 0 3px rgba(212,175,55,0.15);
        }
        .input-wrapper .form-control.is-invalid {
            border-color: #dc3545;
        }
        .input-wrapper .icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #adb5bd;
            font-size: 1.2rem;
        }
        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 25px;
        }
        .form-check .form-check-input:checked {
            background-color: #D4AF37;
            border-color: #D4AF37;
        }
        .form-check label {
            font-size: 0.9rem;
            color: #6c757d;
            cursor: pointer;
            user-select: none;
        }
        .btn-login {
            width: 100%;
            height: 52px;
            border-radius: 12px;
            background: #000;
            color: #D4AF37;
            font-weight: 700;
            font-size: 1rem;
            border: none;
            transition: all 0.3s;
            cursor: pointer;
        }
        .btn-login:hover {
            background: #1a1a1a;
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .auth-footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid #e9ecef;
        }
        .auth-footer p {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }
        .auth-footer a {
            color: #D4AF37;
            font-weight: 700;
            text-decoration: none;
        }
        .auth-footer a:hover { text-decoration: underline; }
        .invalid-feedback {
            font-size: 0.8rem;
            margin-top: 5px;
        }
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            color: #6c757d;
            text-decoration: none;
            font-size: 0.85rem;
            margin-bottom: 25px;
        }
        .back-link:hover { color: #D4AF37; }
        .alert {
            border-radius: 12px;
            font-size: 0.9rem;
            padding: 12px 16px;
        }
    </style>
</head>

<body>
    <div class="auth-container">
        <div class="auth-card">
            <a href="{{ route('clientHome') }}" class="back-link">
                <i class="bi bi-arrow-left"></i> Volver a la tienda
            </a>

            <div class="logo">
                <h1><span>S</span>partan</h1>
            </div>
            <p class="subtitle">Inicia sesión para continuar</p>

            @if(isset($errors) && $errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('customer.login.post') }}">
                @csrf
                <div class="form-group">
                    <label>Correo electrónico</label>
                    <div class="input-wrapper">
                        <i class="bi bi-envelope icon"></i>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="tu@correo.com">
                    </div>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Contraseña</label>
                    <div class="input-wrapper">
                        <i class="bi bi-shield-lock icon"></i>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="••••••••">
                    </div>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember">Recordar sesión</label>
                </div>

                <button type="submit" class="btn-login">Iniciar Sesión</button>
            </form>

            <div class="auth-footer">
                <p>¿No tienes cuenta?</p>
                <a href="{{ route('customer.register') }}">Crear una cuenta</a>
            </div>
        </div>
    </div>
</body>
</html>