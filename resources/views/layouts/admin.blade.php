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
            background: #f8fafc;
            display: flex;
            flex-direction: column;
        }

        .admin-shell nav {
            background: #1e293b;
            color: white;
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
            color: white;
            text-decoration: none;
            padding: 8px 14px;
            border-radius: 6px;
        }

        .admin-shell nav a:hover {
            background: #64748b;
        }

        .admin-shell nav .logout-button {
            background: #2563eb;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            color: white;
            cursor: pointer;
            font-weight: 600;
        }

        .admin-shell nav .logout-button:hover {
            background: #1d4ed8;
        }

        .admin-shell .container {
            max-width: 1200px;
            margin: 32px auto;
            padding: 0 20px;
            flex: 1;
        }

        .footer {
            margin-top: 40px;
            background: #1e293b;
            color: #ffffff;
            padding: 16px 0;
            text-align: center;
            border-top: 1px solid #334155;
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
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    <a href="{{ route('admin.status') }}">Status</a>
                    <a href="{{ route('admin.requests') }}">Requests</a>
                    <a href="{{ route('admin.token.generate') }}">Generate Token</a>
                    <a href="{{ route('admin.tokens.index') }}">Tokens</a>
                    <a href="{{ route('admin.managers.index') }}">Managers</a>
                    <a href="{{ route('admin.managers.create') }}">Create Manager</a>
                    <a href="{{ route('admin.users.deleted') }}">Deleted Users</a>
                </div>

                <div class="nav-links" style="gap: 12px;">
                    <span style="font-weight: 600;">{{ auth('admin')->user()->name ?? 'Admin' }}</span>
                    <span style="opacity: 0.8;">{{ auth('admin')->user()->email ?? '' }}</span>
                    <form method="POST" action="{{ route('admin.logout') }}" style="display:inline; margin:0;">
                        @csrf
                        <button type="submit" class="logout-button">Logout</button>
                    </form>
                </div>
            </nav>
        @endauth

        @yield('admin_content')

        <footer class="footer">
            <div class="container">
                <p>© {{ date('Y') }} Admin Panel. All rights reserved.</p>
            </div>
        </footer>
    </div>
@endsection
