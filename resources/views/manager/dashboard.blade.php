@extends('layouts.manager')

@section('title', 'Manager Dashboard')

@section('styles')
    <style>
        .manager-dashboard .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 24px;
            margin-top: 24px;
        }

        .manager-dashboard .card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 18px;
            padding: 28px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, .08);
        }

        .manager-dashboard .card h2 {
            margin-bottom: 14px;
            color: #0f172a;
        }

        .manager-dashboard .button {
            background: #2563eb;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 10px;
            text-decoration: none;
            display: inline-block;
            font-weight: 600;
            margin-top: 16px;
        }

        .manager-dashboard .button:hover {
            background: #1d4ed8;
        }
    </style>
@endsection

@section('manager_content')
    <div class="container manager-dashboard">
        <h1>Manager Dashboard</h1>
        <p>Only the actions you are allowed to take are available here.</p>

        <div class="grid">
            <div class="card">
                <h2>Users</h2>
                <p>View users in the system and disable user accounts as needed.</p>
                <a href="{{ route('manager.users.index') }}" class="button">Manage Users</a>
            </div>

            <div class="card">
                <h2>Users at a glance</h2>
                <p>Total users: <strong>{{ $totalUsers }}</strong></p>
                <p>Disabled users: <strong>{{ $disabledUsers }}</strong></p>
            </div>
        </div>
    </div>
@endsection
