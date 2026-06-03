@extends('layouts.admin')

@section('title', 'Managers')

@section('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background: #f8fafc;
            color: #0f172a;
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
            background: #ffffff;
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

        .message {
            margin-bottom: 16px;
            padding: 12px 14px;
            border-radius: 8px;
        }

        .success {
            background: #ecfdf5;
            color: #166534;
            border: 1px solid #bbf7d0;
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

        .actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .danger {
            background: #2563eb;
        }

        .danger:hover {
            background: #1d4ed8;
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
            color: #64748b;
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
            <h1>Managers</h1>
            <p>Review and manage all managers in the application.</p>

            @if (session('success'))
                <div class="message success">{{ session('success') }}</div>
            @endif

            <div
                style="margin-top: 18px; display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;">
                <form method="GET" action="{{ route('admin.managers.index') }}"
                    style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
                    <input type="text" name="search" placeholder="Search managers by name or email"
                        value="{{ request('search') }}"
                        style="padding:10px 14px; border:1px solid #cbd5e1; border-radius:8px; min-width:240px;" />
                    <button type="submit" class="button secondary">Search</button>
                    @if (request('search'))
                        <a href="{{ route('admin.managers.index') }}" class="button secondary">Clear</a>
                    @endif
                </form>
                <a href="{{ route('admin.managers.create') }}" class="button">Create Manager</a>
                <a href="{{ route('admin.managers.deleted') }}" class="button secondary">Show Deleted Managers</a>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($managers as $manager)
                        <tr>
                            <td>{{ $manager->name }}</td>
                            <td>{{ $manager->email }}</td>
                            <td>{{ $manager->created_at->format('Y-m-d') }}</td>
                            <td class="actions">
                                <a href="{{ route('admin.managers.edit', $manager) }}" class="button secondary">Edit</a>
                                <form method="POST" action="{{ route('admin.managers.destroy', $manager) }}"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="button danger"
                                        onclick="return confirm('Delete this manager?');">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="padding: 20px 12px; text-align: center; color: #475569;">No
                                managers found yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="pagination-simple">
                @if (!$managers->onFirstPage())
                    <a href="{{ $managers->previousPageUrl() }}">←</a>
                @endif

                <span>
                    {{ $managers->currentPage() }} / {{ $managers->lastPage() }}
                </span>

                @if ($managers->hasMorePages())
                    <a href="{{ $managers->nextPageUrl() }}">→</a>
                @endif
            </div>
            <div class="page-jump">
                <span>
                    Page {{ $managers->currentPage() }} of {{ $managers->lastPage() }}
                </span>

                <form method="GET" action="{{ route('admin.managers.index') }}">
                    @foreach (request()->except('page') as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    <input type="number" name="page" min="1" max="{{ $managers->lastPage() }}"
                        value="{{ $managers->currentPage() }}">
                    <button type="submit" class="button secondary">Go</button>
                </form>
            </div>
        </div>
    </div>

@endsection
