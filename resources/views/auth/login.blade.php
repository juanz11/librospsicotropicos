<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesión — {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
            background: #f1f5f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,.08), 0 1px 4px rgba(0,0,0,.04);
            width: 100%;
            max-width: 420px;
            padding: 2.5rem 2rem;
        }

        .logo-wrap {
            display: flex;
            justify-content: center;
            margin-bottom: 1.75rem;
        }

        .logo-wrap img {
            height: 64px;
            width: auto;
            object-fit: contain;
        }

        h1 {
            font-size: 1.375rem;
            font-weight: 700;
            color: #0f172a;
            text-align: center;
            margin-bottom: .375rem;
        }

        .subtitle {
            font-size: .875rem;
            color: #64748b;
            text-align: center;
            margin-bottom: 1.75rem;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            border-radius: 8px;
            padding: .75rem 1rem;
            font-size: .875rem;
            margin-bottom: 1.25rem;
        }

        .form-group {
            margin-bottom: 1.125rem;
        }

        label {
            display: block;
            font-size: .8125rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: .375rem;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: .625rem .875rem;
            border: 1.5px solid #d1d5db;
            border-radius: 8px;
            font-size: .9375rem;
            color: #111827;
            background: #f9fafb;
            transition: border-color .15s, box-shadow .15s;
            outline: none;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,.15);
            background: #fff;
        }

        input.is-invalid {
            border-color: #f87171;
        }

        .field-error {
            font-size: .8rem;
            color: #dc2626;
            margin-top: .3rem;
        }

        .remember-row {
            display: flex;
            align-items: center;
            gap: .5rem;
            margin-bottom: 1.5rem;
        }

        .remember-row input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #6366f1;
            cursor: pointer;
        }

        .remember-row label {
            margin: 0;
            font-weight: 400;
            font-size: .875rem;
            color: #4b5563;
            cursor: pointer;
        }

        .btn-primary {
            width: 100%;
            padding: .7rem 1rem;
            background: #6366f1;
            color: #fff;
            font-size: .9375rem;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background .15s, transform .1s;
        }

        .btn-primary:hover  { background: #4f46e5; }
        .btn-primary:active { transform: scale(.98); }
    </style>
</head>
<body>

<div class="card">

    {{-- Logo --}}
    <div class="logo-wrap">
        <img src="{{ asset('logo.png') }}" alt="{{ config('app.name') }} logo">
    </div>

    <h1>Panel Administrativo</h1>
    <p class="subtitle">Ingresa tus credenciales para continuar</p>

    {{-- Error general --}}
    @if ($errors->any())
        <div class="alert-error">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" novalidate>
        @csrf

        {{-- Email --}}
        <div class="form-group">
            <label for="email">Correo electrónico</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                autocomplete="email"
                autofocus
                class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                placeholder="admin@ejemplo.com"
            >
            @error('email')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Contraseña --}}
        <div class="form-group">
            <label for="password">Contraseña</label>
            <input
                type="password"
                id="password"
                name="password"
                autocomplete="current-password"
                class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                placeholder="••••••••"
            >
            @error('password')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Recordarme --}}
        <div class="remember-row">
            <input type="checkbox" id="remember" name="remember">
            <label for="remember">Recordarme</label>
        </div>

        <button type="submit" class="btn-primary">Iniciar sesión</button>
    </form>

</div>

</body>
</html>
