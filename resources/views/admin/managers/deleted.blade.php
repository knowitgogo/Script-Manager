@extends('layouts.admin')

@section('title', 'Deleted Managers')

@section('admin_content')
    <div class="container">
        <div class="card">

            <div style="display:flex;justify-content:space-between;align-items:center;">
                <div>
                    <h1>Deleted Managers</h1>
                    <p>All soft deleted managers.</p>
                </div>

                <a href="{{ route('admin.managers.index') }}" class="button">
                    Active Managers
                </a>
            </div>

            @if ($managers->isEmpty())
                <div class="empty">
                    No deleted managers found.
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
                        @foreach ($managers as $manager)
                            <tr>
                                <td>{{ $manager->name }}</td>
                                <td>{{ $manager->email }}</td>
                                <td>{{ $manager->deleted_at->format('Y-m-d H:i') }}</td>

                                <td>
                                    <div style="display:flex;gap:8px;">

                                        <form method="POST" action="{{ route('admin.managers.restore', $manager->id) }}">
                                            @csrf

                                            <button type="submit" class="button success">
                                                Restore
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
