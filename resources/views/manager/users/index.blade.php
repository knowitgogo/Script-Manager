@extends('layouts.manager')

@section('title', 'Manage Users')

@section('styles')
    <style>
        .manager-users .filter-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
            justify-content: space-between;
            margin: 18px 0;
        }

        .manager-users .button {
            background: #2563eb;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
        }

        .manager-users .button.secondary {
            background: #64748b;
        }

        .manager-users .button.success {
            background: #16a34a;
        }

        .manager-users .button.success:hover {
            background: #15803d;
        }

        .manager-users .button:hover {
            background: #1d4ed8;
        }

        .manager-users table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .manager-users th,
        .manager-users td {
            padding: 14px 12px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
        }

        .manager-users th {
            background: #f1f5f9;
            color: #0f172a;
        }

        .manager-users tr:hover {
            background: #f8fafc;
        }

        .manager-users .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 700;
        }

        .manager-users .status-active {
            background: #d1fae5;
            color: #065f46;
        }

        .manager-users .status-disabled {
            background: #fee2e2;
            color: #991b1b;
        }

        .pagination-simple {
            margin-top: 20px;
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            align-items: center;
        }

        .pagination-simple a,
        .pagination-simple span {
            padding: 6px 12px;
            font-size: 13px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            text-decoration: none;
        }

        .pagination-simple a {
            background: white;
            color: #334155;
        }

        .pagination-simple a:hover {
            background: #f1f5f9;
        }

        .pagination-simple .disabled {
            color: #94a3b8;
            background: #f8fafc;
            cursor: not-allowed;
        }

        .page-jump form {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .page-jump input {
            width: 90px;
            padding: 8px 10px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
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

@section('manager_content')
    <div class="container manager-users">
        <h1>Users</h1>
        <p>Managers can only disable user accounts; create, update, and delete actions are not available.</p>

        @if (session('success'))
            <div class="message success"
                style="margin-bottom:16px; padding:12px 14px; border-radius:10px; background:#d1fae5; color:#065f46;">
                {{ session('success') }}
            </div>
        @endif

        <div class="filter-bar">
            <form method="GET" action="{{ route('manager.users.index') }}"
                style="display:flex; gap:8px; flex-wrap:wrap; align-items:center;">
                <input type="text" name="search" placeholder="Search by name or email" value="{{ request('search') }}"
                    style="padding:10px 14px; border:1px solid #cbd5e1; border-radius:8px; min-width:240px;" />
                <button type="submit" class="button secondary">Search</button>
                @if (request('search'))
                    <a href="{{ route('manager.users.index') }}" class="button secondary">Clear</a>
                @endif
            </form>
            <div style="color:#475569; font-size:14px;">Showing {{ $users->total() }} users</div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if ($user->disabled)
                                <span class="status-badge status-disabled">Disabled</span>
                            @else
                                <span class="status-badge status-active">Active</span>
                            @endif
                        </td>
                        <td>
                            @if (!$user->disabled)
                                <form method="POST" action="{{ route('manager.users.disable', $user) }}"
                                    style="display:inline;">
                                    @csrf
                                    <button type="submit" class="button">Disable</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('manager.users.enable', $user) }}"
                                    style="display:inline;">
                                    @csrf
                                    <button type="submit" class="button success">Enable</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="padding: 20px 12px; text-align: center; color: #475569;">No users found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="pagination-simple">
            @if ($users->onFirstPage())
                <span class="disabled">← Previous</span>
            @else
                <a href="{{ $users->previousPageUrl() }}">← Previous</a>
            @endif

            @if ($users->hasMorePages())
                <a href="{{ $users->nextPageUrl() }}">Next →</a>
            @else
                <span class="disabled">Next →</span>
            @endif
        </div>
        <div class="page-jump">
            <span>
                Page {{ $users->currentPage() }} of {{ $users->lastPage() }}
            </span>

            <form method="GET" action="{{ route('manager.users.index') }}">
                @foreach (request()->except('page') as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
                <input type="number" name="page" min="1" max="{{ $users->lastPage() }}"
                    value="{{ $users->currentPage() }}">
                <button type="submit" class="button secondary">Go</button>
            </form>
        </div>
    </div>
@endsection
