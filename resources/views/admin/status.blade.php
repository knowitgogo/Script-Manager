@extends('layouts.admin')

@section('title', 'Admin Status')

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

        nav .logout {
            background: #2563eb;
        }

        nav .logout:hover {
            background: #1d4ed8;
        }

        .container {
            max-width: 1200px;
            margin: 32px auto;
            padding: 0 20px;
        }

        .card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 28px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, .08);
        }

        .status-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .status-item:last-child {
            border-bottom: none;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-badge.active {
            background: #d1fae5;
            color: #065f46;
        }

        .status-badge.inactive {
            background: #fee2e2;
            color: #991b1b;
        }
    </style>
@endsection

@section('admin_content')

    <div class="container">
        <div class="card">
            <h1>System Status</h1>

            <div class="status-item">
                <span>Database Connection</span>
                <span class="status-badge active">Active</span>
            </div>

            <div class="status-item">
                <span>Cache Server</span>
                <span class="status-badge active">Active</span>
            </div>

            <div class="status-item">
                <span>Total Admins</span>
                <span>{{ $totalAdmins ?? 0 }}</span>
            </div>

            <div class="status-item">
                <span>Total Managers</span>
                <span>{{ $totalManagers ?? 0 }}</span>
            </div>
            <div class="status-item">
                <span>Total Users</span>
                <span>{{ $totalUsers ?? 0 }}</span>
            </div>

        </div>
    </div>
    </div>

@endsection
