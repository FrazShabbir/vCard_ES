<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Customizer') | {{ config('app.name') }}</title>
    <link rel="shortcut icon" href="{{ asset(fromSettings('favicon') ?? 'frontend/assets/images/logo.png') }}" />
    <link rel="stylesheet" href="{{ asset('backend/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Inter', sans-serif; overflow: hidden; }
        .customizer-wrapper { display: flex; flex-direction: column; height: 100vh; }
        .customizer-topbar { height: 56px; background: #1a1a2e; color: #fff; display: flex; align-items: center; justify-content: space-between; padding: 0 1.5rem; flex-shrink: 0; }
        .customizer-topbar a { color: #fff; text-decoration: none; }
        .customizer-topbar .brand { font-weight: 700; font-size: 1.1rem; }
        .customizer-topbar .actions { display: flex; align-items: center; gap: 1rem; }
        .customizer-topbar .btn { padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.9rem; cursor: pointer; border: none; }
        .customizer-topbar .btn-outline { background: transparent; border: 1px solid rgba(255,255,255,0.5); color: #fff; }
        .customizer-topbar .btn-primary { background: #0d6efd; color: #fff; }
        .customizer-content { flex: 1; display: flex; overflow: hidden; min-height: 0; }
        @stack('css')
    </style>
    @stack('css')
</head>
<body>
    <div class="customizer-wrapper">
        <header class="customizer-topbar">
            <a href="{{ route('user.customizer') }}" class="brand">
                <i class="fas fa-arrow-left"></i> {{ config('app.name') }} Customizer
            </a>
            <div class="actions">
                <a href="{{ url(auth()->user()->username) }}" target="_blank" class="btn btn-outline">
                    <i class="fas fa-external-link-alt"></i> View live
                </a>
                <button type="button" id="customizer-save-btn" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save
                </button>
            </div>
        </header>
        <div class="customizer-content">
            @yield('content')
        </div>
    </div>
    <script src="{{ asset('backend/js/jquery.min.js') }}"></script>
    @stack('scripts')
</body>
</html>
