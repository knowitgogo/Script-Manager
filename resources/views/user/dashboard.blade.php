<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: ui-sans-serif, system-ui, sans-serif; background: #f8fafc; color: #111827; }
        nav { background: #1e293b; color: white; padding: 16px 32px; display: flex; justify-content: space-between; align-items: center; }
        nav a { color: white; text-decoration: none; padding: 8px 16px; margin: 0 8px; border-radius: 6px; }
        nav a:hover { background: #334155; }
        .container { max-width: 1200px; margin: 32px auto; padding: 20px; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 24px; margin-top: 32px; }
        .card { background: white; border: 1px solid #e5e7eb; border-radius: 12px; padding: 28px; box-shadow: 0 10px 30px rgba(15,23,42,.08); }
        .button { background: #2563eb; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block; }
        .button:hover { background: #1d4ed8; }
        .user-info { display: flex; flex-direction: column; gap: 4px; }
        .user-info span { opacity: 0.85; }
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
        <div class="user-info">
            <strong>{{ auth()->user()->name }}</strong>
            <span>{{ auth()->user()->email }}</span>
        </div>
        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <button type="submit" class="button" style="background:#dc2626;">Logout</button>
        </form>
    </nav>

    <div class="container">
        <h1>User Dashboard</h1>
        <p>Welcome back, {{ auth()->user()->name }}. Use the links above to check your status, requests, or generate a token.</p>

        <div class="grid">
            <div class="card">
                <h2>Status</h2>
                <p>Review your account status and details.</p>
                <a href="{{ route('status') }}" class="button">View Status</a>
            </div>
            <div class="card">
                <h2>Requests</h2>
                <p>View your requests and activity.</p>
                <a href="{{ route('requests') }}" class="button">View Requests</a>
            </div>
            <div class="card">
                <h2>My Tokens</h2>
                <p>View, edit, or delete your saved tokens.</p>
                <a href="{{ route('tokens.index') }}" class="button">Manage Tokens</a>
            </div>
            <div class="card">
                <h2>Generate Token</h2>
                <p>Create a personal token for API or integrations.</p>
                <a href="{{ route('token.generate') }}" class="button">Generate Token</a>
            </div>
        </div>
    </div>
</body>
</html>
