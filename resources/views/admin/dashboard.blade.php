@extends('layouts.admin')

@section('title', 'Admin Dashboard')

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

        .container {
            max-width: 1200px;
            margin: 32px auto;
            padding: 0 20px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
            margin-top: 32px;
        }

        .card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 18px;
            padding: 28px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, .08);
        }

        .card h2 {
            margin-bottom: 16px;
            color: #1e293b;
        }

        .card p {
            color: #475569;
            margin-bottom: 18px;
        }

        .button {
            background: #2563eb;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .button:hover {
            background: #1d4ed8;
        }

        .button.secondary {
            background: #64748b;
        }

        .button.secondary:hover {
            background: #475569;
        }
    </style>
@endsection

@section('admin_content')
    <div class="container">
        <h1>Admin Dashboard</h1>

        <div class="grid">
            <div class="card">
                <h2>Status</h2>
                <p>View system status and statistics</p>
                <a href="{{ route('admin.status') }}" class="button">View Status</a>
            </div>

            <div class="card">
                <h2>Requests</h2>
                <p>View and manage pending requests</p>
                <a href="{{ route('admin.requests') }}" class="button">View Requests</a>
            </div>

            <div class="card">
                <h2>Users</h2>
                <p>View all registered users</p>
                <a href="{{ route('admin.users.index') }}" class="button">View Users</a>
            </div>

            <div class="card">
                <h2>Generate Token</h2>
                <p>Generate API tokens for integration</p>
                <a href="{{ route('admin.token.generate') }}" class="button">Generate Token</a>
            </div>

            <div class="card">
                <h2>Tokens</h2>
                <p>Review token records created by users.</p>
                <a href="{{ route('admin.tokens.index') }}" class="button">View Tokens</a>
            </div>

            <div class="card">
                <h2>Activity Summary</h2>
                <p>Total token records currently stored in the system.</p>
                <a href="{{ route('admin.tokens.index') }}" class="button secondary">{{ $totalTokens ?? 0 }} Tokens</a>
            </div>

            <div class="card">
                <h2>Create Manager</h2>
                <p>Add a new manager to the system</p>
                <a href="{{ route('admin.managers.create') }}" class="button">Create Manager</a>
            </div>
            <div class="card">
                <h2>Show Deleted Users</h2>
                <p>View users who have been deleted</p>
                <a href="{{ route('admin.users.deleted') }}" class="button">Show Deleted Users</a>
            </div>
            <div class="card">
                <h2>Show Deleted Managers</h2>
                <p>View managers who have been deleted</p>
                <a href="{{ route('admin.managers.deleted') }}" class="button">Show Deleted Managers</a>
            </div>

        </div>
    </div>
@endsection
