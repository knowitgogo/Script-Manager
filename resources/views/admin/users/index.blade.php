@extends('layouts.admin')

@section('title', 'Users')

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
            padding: 10px 18px;
            border-radius: 8px;
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

        .button.danger {
            background: #2563eb;
        }

        .button.danger:hover {
            background: #1d4ed8;
        }

        .button.success {
            background: #16a34a;
        }

        .button.success:hover {
            background: #15803d;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 14px 12px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
        }

        th {
            background: #f1f5f9;
            color: #0f172a;
        }

        tr:hover {
            background: #f8fafc;
        }

        .empty {
            text-align: center;
            color: #475569;
            padding: 40px 0;
        }

        .admin-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .admin-info div {
            line-height: 1.2;
        }

        .admin-info small {
            display: block;
            opacity: 0.8;
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

        .page-jump {
            margin-top: 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            font-size: 14px;
            color: #475569;
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
    </style>
@endsection

@section('admin_content')

    <div class="container">
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;">
                <div>
                    <h1>Users</h1>
                    <p>All registered users in the application.</p>
                </div>
                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                    <a href="{{ route('admin.users.create') }}" class="button">Create User</a>
                    <a href="{{ route('admin.managers.index') }}" class="button">View Managers</a>
                </div>
            </div>

            <div
                style="margin-top:18px; display:flex; gap:8px; flex-wrap:wrap; align-items:center; justify-content:space-between;">
                <form method="GET" action="{{ route('admin.users.index') }}"
                    style="display:flex; gap:8px; flex-wrap:wrap; align-items:center;">
                    <input type="text" name="search" placeholder="Search users by name or email"
                        value="{{ request('search') }}"
                        style="padding:10px 14px; border:1px solid #cbd5e1; border-radius:8px; min-width:240px;" />
                    <button type="submit" class="button secondary">Search</button>
                    @if (request('search'))
                        <a href="{{ route('admin.users.index') }}" class="button secondary">Clear</a>
                    @endif
                </form>
                <div style="font-size:14px; color:#475569;">
                    Showing {{ $users->total() }} users
                </div>
            </div>

            @if ($users->isEmpty())
                <div class="empty">No users found.</div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Registered</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->disabled ? 'Disabled' : 'Active' }}</td>
                                <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <div style="display:flex; gap:8px; flex-wrap: wrap;">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="button secondary">Edit</a>
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="button danger"
                                                onclick="return confirm('Delete this user?');">Delete</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}"
                                            style="display:inline;">
                                            @csrf
                                            <button type="submit" class="button success"
                                                onclick="return confirm('{{ $user->disabled ? 'Enable' : 'Disable' }} this user?');">
                                                {{ $user->disabled ? 'Enable' : 'Disable' }}
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @endforeach
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

                    <form method="GET" action="{{ route('admin.users.index') }}">
                        @foreach (request()->except('page') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <input type="number" name="page" min="1" max="{{ $users->lastPage() }}"
                            value="{{ $users->currentPage() }}">
                        <button type="submit" class="button secondary">Go</button>
                    </form>
                </div>
                <div style="margin-top:15px; font-size:14px; color:#64748b;">
                    Total Records: {{ $users->total() }} |
                    Current Page: {{ $users->currentPage() }} |
                    Total Pages: {{ $users->lastPage() }}
                </div>
            @endif
        </div>
    </div>

@endsection
