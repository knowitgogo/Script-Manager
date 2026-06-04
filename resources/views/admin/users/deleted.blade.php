@extends('layouts.admin')

@section('title', __('messages.deleted_users'))

@section('admin_content')
    <div class="container">
        <div class="card">

            <div style="display:flex;justify-content:space-between;align-items:center;">
                <div>
                    <h1>{{ __('messages.deleted_users') }}</h1>
                    <p>{{ __('messages.deleted_users') }}.</p>
                </div>

                <a href="{{ route('admin.users.index') }}" class="button">
                    {{ __('messages.active_users') }}
                </a>
            </div>

            @if ($users->isEmpty())
                <div class="empty">
                    {{ __('messages.no_deleted_users') }}
                </div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('messages.label_name') }}</th>
                            <th>{{ __('messages.label_email') }}</th>
                            <th>{{ __('messages.deleted_at') ?? 'Deleted At' }}</th>
                            <th>{{ __('messages.label_actions') }}</th>
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
                                                {{ __('messages.restore') }}
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.users.force-delete', $user->id) }}">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="button danger"
                                                onclick="return confirm('{{ __('messages.confirm_delete') }}')">
                                                {{ __('messages.delete_permanently') }}
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
