@extends('layouts.app')

@section('layout-styles')

    <head>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
            rel="stylesheet">
    </head>
    <style>
        .admin-shell {
            font-family: 'Nunito', sans-serif;
            min-height: 100vh;
            background: var(--color-bg);
            display: flex;
            flex-direction: column;
        }

        .admin-shell nav {
            background: var(--color-nav-bg);
            color: var(--color-nav-text);
            padding: 16px 32px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            border-radius: 0;
        }

        .admin-shell nav .nav-links {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
        }

        .admin-shell nav a {
            color: var(--color-nav-text);
            text-decoration: none;
            padding: 8px 14px;
            border-radius: 6px;
        }

        .admin-shell nav a:hover {
            background: var(--color-nav-hover);
        }

        .admin-shell nav .logout-button {
            background: var(--color-primary);
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            color: #ffffff;
            cursor: pointer;
            font-weight: 600;
        }

        .admin-shell nav .logout-button:hover {
            background: var(--color-primary-hover);
        }

        .admin-shell .container {
            max-width: 1200px;
            margin: 32px auto;
            padding: 0 20px;
            flex: 1;
        }

        .footer {
            margin-top: 40px;
            background: var(--color-nav-bg);
            color: #ffffff;
            padding: 16px 0;
            text-align: center;
            border-top: 1px solid var(--color-border);
        }

        .footer .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .footer p {
            margin: 0;
            font-size: 14px;
            opacity: 0.9;
        }
    </style>
@endsection

@section('content')
    <div class="admin-shell">
        @auth('admin')
            <nav>
                <div class="nav-links">
                    <a href="{{ route('admin.dashboard') }}">{{ __('messages.dashboard') }}</a>
                    <a href="{{ route('admin.status') }}">{{ __('messages.status') }}</a>
                    <a href="{{ route('admin.requests') }}">{{ __('messages.requests') }}</a>
                    <a href="{{ route('admin.token.generate') }}">{{ __('messages.generate_token') }}</a>
                    <a href="{{ route('admin.tokens.index') }}">{{ __('messages.tokens') }}</a>
                    <a href="{{ route('admin.managers.index') }}">{{ __('messages.managers') }}</a>
                    <a href="{{ route('admin.managers.create') }}">{{ __('messages.create_manager') }}</a>
                </div>

                <div class="nav-links" style="gap: 12px; align-items: center;">
                    <form method="GET" action="{{ route('locale.switch') }}" style="display:inline; margin:0;">
                        <select name="lang" onchange="this.form.submit()" style="padding:6px 8px; border-radius:6px;">
                            <option value="nl" {{ app()->getLocale() == 'nl' ? 'selected' : '' }}>NL</option>
                            <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>EN</option>
                        </select>
                    </form>
                    <span style="font-weight: 600;">{{ auth('admin')->user()->name ?? 'Admin' }}</span>
                    <span style="opacity: 0.8;">{{ auth('admin')->user()->email ?? '' }}</span>
                    <form method="POST" action="{{ route('admin.logout') }}" style="display:inline; margin:0;">
                        @csrf
                        <button type="submit" class="logout-button">{{ __('messages.logout') }}</button>
                    </form>
                </div>
            </nav>
        @endauth

        @yield('admin_content')

        <footer class="footer">
            <div class="container">
                <p>{{ __('messages.admin_panel_copyright', ['year' => date('Y')]) }}</p>
            </div>
        </footer>
    </div>
@endsection
