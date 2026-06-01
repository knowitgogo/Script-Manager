<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tokens</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: ui-sans-serif, system-ui, sans-serif; background: #f8fafc; color: #111827; }
        nav { background: #1e293b; color: white; padding: 16px 32px; display: flex; justify-content: space-between; align-items: center; }
        nav a { color: white; text-decoration: none; padding: 8px 16px; margin: 0 8px; border-radius: 6px; }
        nav a:hover { background: #334155; }
        .container { max-width: 1200px; margin: 32px auto; padding: 20px; }
        .card { background: white; border: 1px solid #e5e7eb; border-radius: 12px; padding: 28px; box-shadow: 0 10px 30px rgba(15,23,42,.08); }
        .button { background: #2563eb; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block; }
        .button:hover { background: #1d4ed8; }
        .secondary { background: #e2e8f0; color: #0f172a; }
        .table { width: 100%; border-collapse: collapse; margin-top: 24px; }
        .table th, .table td { padding: 14px 16px; border: 1px solid #e2e8f0; text-align: left; }
        .table th { background: #f8fafc; }
        .empty { padding: 24px; background: #f8fafc; border-radius: 12px; }
        .message { margin-bottom: 16px; padding: 12px 14px; border-radius: 8px; }
        .success { background: #ecfdf5; color: #166534; border: 1px solid #bbf7d0; }
    </style>
</head>
<body>
    <nav>
        <div>
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <a href="{{ route('status') }}">Status</a>
            <a href="{{ route('requests') }}">Requests</a>
            <a href="{{ route('tokens.index') }}">My Tokens</a>
            <a href="{{ route('token.generate') }}">Generate Token</a>
        </div>
        <div style="display: flex; flex-direction: column; gap: 4px; color: white; font-size: 14px;">
            <strong>{{ auth()->user()->name }}</strong>
            <span style="opacity: 0.85;">{{ auth()->user()->email }}</span>
        </div>
        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <button type="submit" class="button" style="background:#dc2626;">Logout</button>
        </form>
    </nav>

    <div class="container">
        <div class="card">
            <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap;">
                <div>
                    <h1>My Tokens</h1>
                    <p>View and manage the tokens you've created.</p>
                </div>
                <a href="{{ route('token.generate') }}" class="button">Create New Token</a>
            </div>

            @if (session('success'))
                <div class="message success">{{ session('success') }}</div>
            @endif

            @if (session('token'))
                <div class="message success">
                    <strong>New token created:</strong>
                    <div style="margin-top:8px; word-break: break-all; font-family: monospace;">{{ session('token') }}</div>
                </div>
            @endif

            @if ($tokens->isEmpty())
                <div class="empty">
                    <p>No tokens found yet. Create a token to get started.</p>
                </div>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Token</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tokens as $token)
                            <tr>
                                <td>{{ $token->name }}</td>
                                <td style="font-family:monospace;">{{ $token->token }}</td>
                                <td>{{ $token->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a href="{{ route('tokens.edit', $token) }}" class="button secondary">Edit</a>
                                    <form method="POST" action="{{ route('tokens.destroy', $token) }}" style="display:inline-block; margin-left:8px;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="button" style="background:#dc2626;">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</body>
</html>
