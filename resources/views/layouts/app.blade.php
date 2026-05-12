<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Panel') — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet"/>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #eef2ff;
            --sidebar-w: 240px;
            --header-h: 56px;
            --text: #0f172a;
            --muted: #64748b;
            --border: #e2e8f0;
            --bg: #f8fafc;
            --white: #ffffff;
            --success: #16a34a;
            --warning: #d97706;
            --info: #0284c7;
            --danger: #dc2626;
        }

        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); display: flex; min-height: 100vh; }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-w);
            background: #1e1b4b;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
        }
        .sidebar-brand {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,.1);
            display: flex;
            align-items: center;
            gap: .75rem;
        }
        .sidebar-brand img { height: 36px; width: auto; object-fit: contain; }
        .sidebar-brand span { color: #fff; font-weight: 700; font-size: .9375rem; line-height: 1.2; }

        .sidebar-nav { flex: 1; padding: 1rem 0; overflow-y: auto; }
        .nav-section { padding: .25rem 1rem .5rem; font-size: .6875rem; font-weight: 600; color: rgba(255,255,255,.4); text-transform: uppercase; letter-spacing: .08em; }
        .nav-link {
            display: flex; align-items: center; gap: .625rem;
            padding: .5rem 1.25rem;
            color: rgba(255,255,255,.75);
            text-decoration: none;
            font-size: .875rem;
            font-weight: 500;
            border-left: 3px solid transparent;
            transition: all .15s;
        }
        .nav-link:hover { color: #fff; background: rgba(255,255,255,.07); }
        .nav-link.active { color: #fff; background: rgba(99,102,241,.3); border-left-color: var(--primary); }
        .nav-link svg { width: 18px; height: 18px; flex-shrink: 0; }

        /* ── Main ── */
        .main-wrap { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }

        .topbar {
            height: var(--header-h);
            background: var(--white);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            position: sticky; top: 0; z-index: 50;
        }
        .topbar-title { font-weight: 600; font-size: 1rem; color: var(--text); }
        .topbar-user { display: flex; align-items: center; gap: .75rem; font-size: .875rem; color: var(--muted); }
        .btn-logout {
            padding: .35rem .8rem;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 6px;
            font-size: .8125rem;
            font-weight: 600;
            color: var(--text);
            cursor: pointer;
            transition: background .15s;
        }
        .btn-logout:hover { background: var(--border); }

        .content { padding: 1.5rem; flex: 1; }

        /* ── Cards ── */
        .card { background: var(--white); border-radius: 12px; border: 1px solid var(--border); }
        .card-header { padding: 1rem 1.25rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; }
        .card-header h2 { font-size: 1rem; font-weight: 700; }
        .card-body { padding: 1.25rem; }

        /* ── Buttons ── */
        .btn { display: inline-flex; align-items: center; gap: .4rem; padding: .5rem 1rem; border-radius: 8px; font-size: .875rem; font-weight: 600; cursor: pointer; border: none; text-decoration: none; transition: all .15s; }
        .btn-primary   { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-dark); }
        .btn-secondary { background: var(--bg); color: var(--text); border: 1px solid var(--border); }
        .btn-secondary:hover { background: var(--border); }
        .btn-danger    { background: #fef2f2; color: var(--danger); border: 1px solid #fecaca; }
        .btn-danger:hover { background: #fee2e2; }
        .btn-success   { background: #f0fdf4; color: var(--success); border: 1px solid #bbf7d0; }
        .btn-success:hover { background: #dcfce7; }
        .btn-sm { padding: .3rem .7rem; font-size: .8125rem; }
        .btn-icon { padding: .4rem; border-radius: 6px; }

        /* ── Forms ── */
        .form-group { margin-bottom: 1.125rem; }
        .form-label { display: block; font-size: .8125rem; font-weight: 600; color: #374151; margin-bottom: .375rem; }
        .form-control {
            width: 100%; padding: .575rem .875rem;
            border: 1.5px solid var(--border); border-radius: 8px;
            font-size: .9375rem; color: var(--text); background: #f9fafb;
            transition: border-color .15s, box-shadow .15s; outline: none;
        }
        .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(99,102,241,.15); background: #fff; }
        .form-control.is-invalid { border-color: #f87171; }
        .invalid-feedback { font-size: .8rem; color: var(--danger); margin-top: .3rem; }
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; }

        /* ── Table ── */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: .875rem; }
        thead th { background: var(--primary-light); color: var(--primary-dark); font-weight: 700; padding: .625rem 1rem; text-align: left; white-space: nowrap; }
        tbody tr { border-bottom: 1px solid var(--border); }
        tbody tr:hover { background: #fafafa; }
        tbody td { padding: .625rem 1rem; vertical-align: middle; }

        /* ── Badges ── */
        .badge { display: inline-flex; align-items: center; padding: .2rem .6rem; border-radius: 999px; font-size: .75rem; font-weight: 600; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-info    { background: #e0f2fe; color: #075985; }
        .badge-danger  { background: #fef2f2; color: #991b1b; }

        /* ── Alerts ── */
        .alert { padding: .75rem 1rem; border-radius: 8px; font-size: .875rem; margin-bottom: 1rem; }
        .alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
        .alert-error   { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }

        /* ── Pagination ── */
        .pagination { display: flex; gap: .25rem; flex-wrap: wrap; margin-top: 1rem; }
        .pagination a, .pagination span {
            padding: .35rem .7rem; border-radius: 6px; font-size: .8125rem; font-weight: 500;
            border: 1px solid var(--border); color: var(--text); text-decoration: none;
        }
        .pagination a:hover { background: var(--primary-light); border-color: var(--primary); color: var(--primary); }
        .pagination .active span { background: var(--primary); color: #fff; border-color: var(--primary); }
        .pagination .disabled span { color: #cbd5e1; }

        /* ── Misc ── */
        .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; flex-wrap: wrap; gap: .75rem; }
        .page-header h1 { font-size: 1.25rem; font-weight: 700; }
        .text-muted { color: var(--muted); }
        .text-sm { font-size: .8125rem; }
        .mt-1 { margin-top: .25rem; }
        .mt-2 { margin-top: .5rem; }
        .mt-3 { margin-top: .75rem; }
        .mt-4 { margin-top: 1rem; }
        .flex { display: flex; }
        .gap-2 { gap: .5rem; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .w-full { width: 100%; }

        /* ── Items table (despacho form) ── */
        .items-table { width: 100%; border-collapse: collapse; font-size: .875rem; }
        .items-table th { background: var(--primary-light); color: var(--primary-dark); font-weight: 700; padding: .5rem .75rem; text-align: left; }
        .items-table td { padding: .4rem .5rem; vertical-align: top; border-bottom: 1px solid var(--border); }
        .items-table .form-control { padding: .4rem .6rem; font-size: .875rem; }
        .btn-remove-row { background: #fef2f2; border: 1px solid #fecaca; color: var(--danger); border-radius: 6px; padding: .3rem .6rem; cursor: pointer; font-size: .875rem; font-weight: 700; }
        .btn-remove-row:hover { background: #fee2e2; }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main-wrap { margin-left: 0; }
        }
    </style>
</head>
<body>

{{-- Sidebar --}}
<aside class="sidebar">
    <div class="sidebar-brand">
        <img src="{{ asset('logo.png') }}" alt="Logo">
        <span>{{ config('app.name') }}</span>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section">Principal</div>
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Inicio
        </a>

        <div class="nav-section">Libro Psicotrópicos</div>
        <a href="{{ route('despachos.index') }}" class="nav-link {{ request()->routeIs('despachos.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Despachos
        </a>

        <div class="nav-section">Catálogos</div>
        <a href="{{ route('clientes.index') }}" class="nav-link {{ request()->routeIs('clientes.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            Clientes
        </a>
        <a href="{{ route('productos.index') }}" class="nav-link {{ request()->routeIs('productos.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
            Medicamentos
        </a>
    </nav>
</aside>

{{-- Main --}}
<div class="main-wrap">
    <header class="topbar">
        <span class="topbar-title">@yield('title', 'Panel')</span>
        <div class="topbar-user">
            <span>{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">Salir</button>
            </form>
        </div>
    </header>

    <main class="content">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @yield('content')
    </main>
</div>

@stack('scripts')
</body>
</html>
