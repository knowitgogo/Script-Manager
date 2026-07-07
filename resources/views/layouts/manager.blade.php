@extends('layouts.app')

@section('layout-styles')
    <style>
        .manager-shell {
            min-height: 100vh;
            background: var(--color-bg);
            display: flex;
            flex-direction: column;
        }

        .manager-shell nav {
            background: var(--color-nav-bg);
            color: var(--color-nav-text);
            padding: 16px 32px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }

        .manager-shell nav .nav-links {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
        }

        .manager-shell nav a {
            color: var(--color-nav-text);
            text-decoration: none;
            padding: 8px 14px;
            border-radius: 6px;
        }

        .manager-shell nav a:hover {
            background: var(--color-nav-hover);
        }

        .manager-shell nav .logout-button {
            background: var(--color-primary);
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            color: #ffffff;
            cursor: pointer;
            font-weight: 600;
        }

        .manager-shell nav .logout-button:hover {
            background: var(--color-primary-hover);
        }

        .manager-shell .container {
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
    <div class="manager-shell">
        @auth('manager')
            <nav>
                <div class="nav-links">
                    <a href="{{ route('manager.dashboard') }}">{{ __('messages.dashboard') }}</a>
                    <a href="{{ route('manager.users.index') }}">{{ __('messages.users') }}</a>
                </div>

                <div class="nav-links" style="gap: 12px; align-items: center;">
                    <form method="GET" action="{{ route('locale.switch') }}" style="display:inline; margin:0;">
                        <select name="lang" onchange="this.form.submit()" style="padding:6px 8px; border-radius:6px;">
                            <option value="nl" {{ app()->getLocale() == 'nl' ? 'selected' : '' }}>NL</option>
                            <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>EN</option>
                        </select>
                    </form>
                    <span style="font-weight: 600;">{{ auth('manager')->user()->name ?? 'Manager' }}</span>
                    <span style="opacity: 0.8;">{{ auth('manager')->user()->email ?? '' }}</span>
                    <form method="POST" action="{{ route('manager.logout') }}" style="display:inline; margin:0;">
                        @csrf
                        <button type="submit" class="logout-button">{{ __('messages.logout') }}</button>
                    </form>
                </div>
            </nav>
        @endauth

        @yield('manager_content')


        <footer class="footer">
            <div class="container">
                <p>{{ __('messages.admin_panel_copyright', ['year' => date('Y')]) }}</p>
            </div>
        </footer>
    </div>
@endsection
