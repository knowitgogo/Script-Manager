<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Managers</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: ui-sans-serif, system-ui, sans-serif; background: #f8fafc; color: #111827; }
        nav { background: #1e293b; color: white; padding: 16px 32px; display: flex; justify-content: space-between; align-items: center; }
        nav a { color: white; text-decoration: none; padding: 8px 16px; margin: 0 8px; border-radius: 6px; }
        nav a:hover { background: #334155; }
        nav .logout { background: #dc2626; }
        nav .logout:hover { background: #b91c1c; }
        .container { max-width: 1200px; margin: 32px auto; padding: 20px; }
        .card { background: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 28px; box-shadow: 0 10px 30px rgba(15,23,42,.08); }
        .button { background: #2563eb; color: white; border: none; padding: 10px 18px; border-radius: 8px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block; }
        .button:hover { background: #1d4ed8; }
        .button.secondary { background: #64748b; }
        .button.secondary:hover { background: #475569; }
        .message { margin-bottom: 16px; padding: 12px 14px; border-radius: 8px; }
        .success { background: #ecfdf5; color: #166534; border: 1px solid #bbf7d0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 14px 12px; border-bottom: 1px solid #e5e7eb; text-align: left; }
        th { background: #f1f5f9; color: #0f172a; }
        tr:hover { background: #f8fafc; }
        .actions { display: flex; gap: 8px; flex-wrap: wrap; }
        .danger { background: #dc2626; }
        .danger:hover { background: #b91c1c; }
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
                <button type="submit" class="button logout" style="border: none; padding: 8px 16px;">Logout</button>
            </form>
        </div>
    </nav>

    <div class="container">
        <div class="card">
            <h1>Managers</h1>
            <p>Review and manage all managers in the application.</p>

            @if (session('success'))
                <div class="message success">{{ session('success') }}</div>
            @endif

            <div style="margin-top: 18px; display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;">
                <div></div>
                <a href="{{ route('admin.managers.create') }}" class="button">Create Manager</a>
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
                                <form method="POST" action="{{ route('admin.managers.destroy', $manager) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="button danger" onclick="return confirm('Delete this manager?');">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="padding: 20px 12px; text-align: center; color: #475569;">No managers found yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
