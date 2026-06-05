@extends('layouts.app')

@section('layout-styles')
    <style>
        .user-shell {
            min-height: 100vh;
            background: var(--color-bg);
            display: flex;
            flex-direction: column;
        }

        .user-shell nav {
            background: var(--color-nav-bg);
            color: var(--color-nav-text);
            padding: 16px 32px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }

        .user-shell nav .nav-links {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
        }

        .user-shell nav a {
            color: var(--color-nav-text);
            text-decoration: none;
            padding: 8px 14px;
            border-radius: 6px;
        }

        .user-shell nav a.nav-button {
            background: var(--color-primary);
            color: var(--color-text-on-primary);
            font-weight: 600;
        }

        .user-shell nav a.nav-button:hover {
            background: var(--color-primary-hover);
        }

        .user-shell nav a:hover {
            background: var(--color-nav-hover);
        }

        .user-shell nav .logout-button {
            background: var(--color-primary);
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            color: #ffffff;
            cursor: pointer;
            font-weight: 600;
        }

        .user-shell nav .logout-button:hover {
            background: var(--color-primary-hover);
        }

        .user-shell .container {
            max-width: 1200px;
            margin: 32px auto;
            padding: 0 20px;
            flex: 1;
        }

        .footer .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .footer {
            margin-top: auto;
            background: var(--color-nav-bg);
            color: #fff;
            padding: 16px 0;
            text-align: center;
            border-top: 1px solid var(--color-border);
        }

        .footer p {
            margin: 0;
            font-size: 14px;
        }
    </style>
@endsection

@section('content')
    <div class="user-shell">
        @auth
            <nav>
                <div class="nav-links">
                    <a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a>
                    <a href="{{ route('status') }}">{{ __('messages.status') }}</a>
                    <a href="{{ route('requests') }}">{{ __('messages.requests') }}</a>
                    <a href="{{ route('tokens.index') }}">{{ __('messages.my_tokens') }}</a>
                    <a href="{{ route('token.generate') }}" class="nav-button">{{ __('messages.generate_token') }}</a>
                </div>

                <div class="nav-links" style="gap: 12px; align-items: center;">
                    <form method="GET" action="{{ route('locale.switch') }}" style="display:inline; margin:0;">
                        <select name="lang" onchange="this.form.submit()" style="padding:6px 8px; border-radius:6px;">
                            <option value="nl" {{ app()->getLocale() == 'nl' ? 'selected' : '' }}>NL</option>
                            <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>EN</option>
                        </select>
                    </form>
                    <span style="font-weight: 600;">{{ auth()->user()->name ?? 'User' }}</span>
                    <span style="opacity: 0.8;">{{ auth()->user()->email ?? '' }}</span>
                    <form method="POST" action="{{ route('logout') }}" style="display:inline; margin:0;">
                        @csrf
                        <button type="submit" class="logout-button">{{ __('messages.logout') }}</button>
                    </form>
                </div>
            </nav>
        @endauth
        <div class="container">
            @yield('user_content')
        </div>

    </div>
    <footer class="footer">
        <div class="container">
            <p>{{ __('messages.admin_panel_copyright', ['year' => date('Y')]) }}</p>
        </div>
    </footer>
@endsection
