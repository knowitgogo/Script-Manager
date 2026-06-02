@extends('layouts.user')

@section('title', 'User Status')

@section('styles')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: ui-sans-serif, system-ui, sans-serif;
            background: #f8fafc;
            color: #111827;
        }

        nav {
            background: #1e293b;
            color: white;
            padding: 16px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav a {
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            margin: 0 8px;
            border-radius: 6px;
        }

        nav a:hover {
            background: #64748b;
        }

        .container {
            max-width: 900px;
            margin: 32px auto;
            padding: 20px;
        }

        .card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 28px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, .08);
        }

        .button {
            background: #2563eb;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .button:hover {
            background: #1d4ed8;
        }

        .user-info {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .user-info span {
            opacity: 0.85;
        }

        .status-item {
            margin-top: 16px;
            padding: 16px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            background: #f8fafc;
        }
    </style>
@endsection

@section('user_content')

    <div class="container">
        <div class="card">
            <h1>Account Status</h1>
            <div class="status-item">
                <strong>Name:</strong> {{ auth()->user()->name }}
            </div>
            <div class="status-item">
                <strong>Email:</strong> {{ auth()->user()->email }}
            </div>
            <div class="status-item">
                <strong>Registered:</strong> {{ auth()->user()->created_at->format('Y-m-d') }}
            </div>
            <div class="status-item">
                <strong>Account Status:</strong>
                @if (auth()->user()->disabled)
                    <span style="color: #dc2626; font-weight: 600;">Disabled</span>
                @else
                    <span style="color: #16a34a; font-weight: 600;">Active</span>
                @endif
            </div>
        </div>

    @endsection
