<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Requests</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: ui-sans-serif, system-ui, sans-serif; background: #f8fafc; color: #111827; }
        nav { background: #1e293b; color: white; padding: 16px 32px; display: flex; justify-content: space-between; align-items: center; }
        nav a { color: white; text-decoration: none; padding: 8px 16px; margin: 0 8px; border-radius: 6px; }
        nav a:hover { background: #334155; }
        nav .logout { background: #dc2626; }
        nav .logout:hover { background: #b91c1c; }
        .container { max-width: 1200px; margin: 32px auto; padding: 0 20px; }
        .card { background: white; border: 1px solid #e5e7eb; border-radius: 12px; padding: 28px; box-shadow: 0 10px 30px rgba(15,23,42,.08); }
        .empty { text-align: center; color: #999; padding: 40px 20px; }
    </style>
</head>
<body>
    <nav>
        <div>
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a href="{{ route('admin.status') }}">Status</a>
            <a href="{{ route('admin.requests') }}">Requests</a>
            <a href="{{ route('admin.token.generate') }}">Generate Token</a>
            <a href="{{ route('admin.users.index') }}">Users</a>
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
                <button type="submit" class="status-badge" style="border: none; background: #dc2626; color: white; padding: 8px 16px; cursor: pointer; border-radius: 6px;">Logout</button>
            </form>
        </div>
    </nav>

    <div class="container">
        <div class="card">
            <h1>Pending Requests</h1>
            <div class="empty">
                <p>No pending requests at this time.</p>
            </div>
        </div>
    </div>
</body>
</html>
