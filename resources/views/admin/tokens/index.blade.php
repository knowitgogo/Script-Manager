@extends('layouts.admin')

@section('title', 'Tokens')

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

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th,
        .table td {
            padding: 14px 12px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
            vertical-align: top;
        }

        .table th {
            background: #f1f5f9;
            color: #0f172a;
        }

        .table tr:hover {
            background: #f8fafc;
        }

        .message {
            margin-bottom: 16px;
            padding: 12px 14px;
            border-radius: 8px;
            background: #ecfdf5;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .empty {
            text-align: center;
            color: #475569;
            padding: 40px 0;
        }
    </style>
@endsection

@section('admin_content')
    <div class="container">
        <div class="card">
            <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap;">
                <div>
                    <h1>Tokens</h1>
                    <p>Review token records created in the application.</p>
                </div>
            </div>

            @if (session('success'))
                <div class="message">{{ session('success') }}</div>
            @endif

            <form method="GET" action="{{ route('admin.tokens.index') }}"
                style="margin-top:18px; display:flex; gap:8px; flex-wrap:wrap; align-items:center;">
                <input type="text" name="search" placeholder="Search token name or value" value="{{ request('search') }}"
                    style="padding:10px 14px; border:1px solid #cbd5e1; border-radius:8px; min-width:240px;" />
                <button type="submit" class="button secondary">Search</button>
                @if (request('search'))
                    <a href="{{ route('admin.tokens.index') }}" class="button secondary">Clear</a>
                @endif
            </form>

            @if ($tokens->isEmpty())
                <div class="empty">No tokens found.</div>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Token</th>
                            <th>Owner</th>
                            <th>Status</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tokens as $token)
                            <tr>
                                <td>{{ $token->name }}</td>
                                <td style="font-family: monospace; word-break: break-all;">{{ $token->token }}</td>
                                <td>
                                    {{ $token->user?->name ?? 'Unknown' }}<br>
                                    <small style="color:#64748b;">{{ $token->user?->email ?? '' }}</small>
                                </td>
                                <td>{{ $token->disabled ? 'Disabled' : 'Active' }}</td>
                                <td>{{ $token->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div style="margin-top:24px; display:flex; justify-content:flex-end;">
                    {{ $tokens->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
