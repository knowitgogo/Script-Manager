@extends('layouts.app')

@section('layout-styles')
    <style>
        .manager-shell {
            min-height: 100vh;
            background: #f8fafc;
            display: flex;
            flex-direction: column;
        }

        .manager-shell nav {
            background: #1e293b;
            color: white;
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
            color: white;
            text-decoration: none;
            padding: 8px 14px;
            border-radius: 6px;
        }

        .manager-shell nav a:hover {
            background: #64748b;
        }

        .manager-shell nav .logout-button {
            background: #2563eb;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            color: white;
            cursor: pointer;
            font-weight: 600;
        }

        .manager-shell nav .logout-button:hover {
            background: #1d4ed8;
        }

        .manager-shell .container {
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
    <div class="manager-shell">
        @auth('manager')
            <nav>
                <div class="nav-links">
                    <a href="{{ route('manager.dashboard') }}">Dashboard</a>
                    <a href="{{ route('manager.users.index') }}">Users</a>
                </div>

                <div class="nav-links" style="gap: 12px;">
                    <span style="font-weight: 600;">{{ auth('manager')->user()->name ?? 'Manager' }}</span>
                    <span style="opacity: 0.8;">{{ auth('manager')->user()->email ?? '' }}</span>
                    <form method="POST" action="{{ route('manager.logout') }}" style="display:inline; margin:0;">
                        @csrf
                        <button type="submit" class="logout-button">Logout</button>
                    </form>
                </div>
            </nav>
        @endauth

        @yield('manager_content')


        <footer class="footer">
            <div class="container">
                <p>© {{ date('Y') }} Admin Panel. All rights reserved.</p>
            </div>
        </footer>
    </div>
@endsection
