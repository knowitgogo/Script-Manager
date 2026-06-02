@extends('layouts.app')

@section('layout-styles')
    <style>
        .user-shell {
            min-height: 100vh;
            background: #f8fafc;
            display: flex;
            flex-direction: column;
        }

        .user-shell nav {
            background: #1e293b;
            color: white;
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
            color: white;
            text-decoration: none;
            padding: 8px 14px;
            border-radius: 6px;
        }

        .user-shell nav a:hover {
            background: #64748b;
        }

        .user-shell nav .logout-button {
            background: #2563eb;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            color: white;
            cursor: pointer;
            font-weight: 600;
        }

        .user-shell nav .logout-button:hover {
            background: #1d4ed8;
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
            background: #1e293b;
            color: #fff;
            padding: 16px 0;
            text-align: center;
            border-top: 1px solid #334155;
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
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <a href="{{ route('status') }}">Status</a>
                    <a href="{{ route('requests') }}">Requests</a>
                    <a href="{{ route('tokens.index') }}">My Tokens</a>
                    <a href="{{ route('token.generate') }}">Generate Token</a>
                </div>

                <div class="nav-links" style="gap: 12px;">
                    <span style="font-weight: 600;">{{ auth()->user()->name ?? 'User' }}</span>
                    <span style="opacity: 0.8;">{{ auth()->user()->email ?? '' }}</span>
                    <form method="POST" action="{{ route('logout') }}" style="display:inline; margin:0;">
                        @csrf
                        <button type="submit" class="logout-button">Logout</button>
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
            <p>© {{ date('Y') }} Admin Panel. All rights reserved.</p>
        </div>
    </footer>
@endsection
