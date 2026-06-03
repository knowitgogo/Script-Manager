@extends('layouts.admin')

@section('title', 'Deleted Users')

@section('admin_content')
    <div class="container">
        <div class="card">

            <div style="display:flex;justify-content:space-between;align-items:center;">
                <div>
                    <h1>Deleted Users</h1>
                    <p>All soft deleted users.</p>
                </div>

                <a href="{{ route('admin.users.index') }}" class="button">
                    Active Users
                </a>
            </div>

            @if ($users->isEmpty())
                <div class="empty">
                    No deleted users found.
                </div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Deleted At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->deleted_at->format('Y-m-d H:i') }}</td>
                                <td>

                                    <div style="display:flex;gap:8px;">

                                        <form method="POST" action="{{ route('admin.users.restore', $user->id) }}">
                                            @csrf
                                            <button type="submit" class="button success">
                                                Restore
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.users.force-delete', $user->id) }}">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="button danger"
                                                onclick="return confirm('Permanently delete this user?')">
                                                Delete Permanently
                                            </button>
                                        </form>

                                    </div>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            @endif

        </div>
    </div>
@endsection
