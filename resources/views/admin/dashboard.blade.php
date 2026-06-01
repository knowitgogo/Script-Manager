<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: ui-sans-serif, system-ui, sans-serif; background: #f8fafc; color: #111827; }
        nav { background: #1e293b; color: white; padding: 16px 32px; display: flex; justify-content: space-between; align-items: center; }
        nav a { color: white; text-decoration: none; padding: 8px 16px; margin: 0 8px; border-radius: 6px; }
        nav a:hover { background: #334155; }
        nav .logout { background: #dc2626; }
        nav .logout:hover { background: #b91c1c; }
        .container { max-width: 1200px; margin: 32px auto; padding: 0 20px; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; margin-top: 32px; }
        .card { background: white; border: 1px solid #e5e7eb; border-radius: 12px; padding: 28px; box-shadow: 0 10px 30px rgba(15,23,42,.08); }
        .card h2 { margin-bottom: 16px; color: #1e293b; }
        .card p { color: #666; margin-bottom: 18px; }
        .button { background: #2563eb; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block; }
        .button:hover { background: #1d4ed8; }
        .button.secondary { background: #64748b; }
        .button.secondary:hover { background: #475569; }
    </style>
</head>
<body>
    <nav>
        <div>
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a href="{{ route('admin.status') }}">Status</a>
            <a href="{{ route('admin.requests') }}">Requests</a>
            <a href="{{ route('admin.token.generate') }}">Generate Token</a>
            <a href="{{ route('admin.managers.index') }}">Managers</a>
            <a href="{{ route('admin.managers.create') }}">Create Manager</a>
        </div>
        <div style="display: flex; align-items: center; gap: 16px;">
            <div style="color: white; font-size: 14px;">
                <div style="font-weight: 600;">{{ auth('admin')->user()->name ?? 'Admin' }}</div>
                <div style="font-size: 12px; opacity: 0.9;">{{ auth('admin')->user()->email ?? '' }}</div>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}" style="display:inline;">
                @csrf
                <button type="submit" class="button logout" style="border: none; padding: 8px 16px;">Logout</button>
            </form>
        </div>
    </nav>

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
                <h2>Create Manager</h2>
                <p>Add a new manager to the system</p>
                <a href="{{ route('admin.managers.create') }}" class="button">Create Manager</a>
            </div>
        </div>
    </div>
</body>
</html>
