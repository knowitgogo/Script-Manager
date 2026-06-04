@extends('layouts.admin')

@section('title', __('messages.users'))

@section('admin_content')

    <div class="container">
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;">
                <div>
                    <h1>{{ __('messages.users') }}</h1>
                    <p>{{ __('messages.total_records') }}: {{ $users->total() }}</p>
                </div>
                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                    <a href="{{ route('admin.users.deleted') }}" class="button">{{ __('messages.show_deleted') }}</a>
                    <a href="{{ route('admin.users.create') }}" class="button">{{ __('messages.create_user') }}</a>
                    <a href="{{ route('admin.managers.index') }}" class="button">{{ __('messages.view_managers') }}</a>
                </div>
            </div>

            <div style="margin-top:18px;">
                <form method="GET" action="{{ route('admin.users.index') }}"
                    style="display:flex; gap:8px; flex-wrap:wrap; align-items:center;">
                    <input type="text" name="search" placeholder="{{ __('messages.search') }}..."
                        value="{{ request('search') }}" />
                    <button type="submit" class="button secondary">{{ __('messages.search') }}</button>
                    @if (request('search'))
                        <a href="{{ route('admin.users.index') }}" class="button secondary">{{ __('messages.clear') }}</a>
                    @endif
                </form>
            </div>

            @if ($users->isEmpty())
                <div class="empty">{{ __('messages.no_records') }}</div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('messages.label_name') }}</th>
                            <th>{{ __('messages.label_email') }}</th>
                            <th>{{ __('messages.status') }}</th>
                            <th>{{ __('messages.label_registered') }}</th>
                            <th>{{ __('messages.label_actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->disabled ? __('messages.disabled') : __('messages.active') }}</td>
                                <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <div style="display:flex; gap:8px; flex-wrap: wrap;">
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                            class="button secondary">{{ __('messages.edit') }}</a>
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="button danger"
                                                onclick="return confirm('{{ __('messages.confirm_delete') }}');">{{ __('messages.delete') }}</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}"
                                            style="display:inline;">
                                            @csrf
                                            <button type="submit" class="button success"
                                                onclick="return confirm('{{ $user->disabled ? __('messages.enable') : __('messages.disable') }}');">
                                                {{ $user->disabled ? __('messages.enable') : __('messages.disable') }}
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="pagination-simple">
                    @if ($users->onFirstPage())
                        <span class="disabled">{{ __('messages.previous') }}</span>
                    @else
                        <a href="{{ $users->previousPageUrl() }}">{{ __('messages.previous') }}</a>
                    @endif

                    @if ($users->hasMorePages())
                        <a href="{{ $users->nextPageUrl() }}">{{ __('messages.next') }}</a>
                    @else
                        <span class="disabled">{{ __('messages.next') }}</span>
                    @endif
                </div>
                <div class="page-jump">
                    <span>
                        {{ __('messages.page') }} {{ $users->currentPage() }} {{ __('messages.of') }} {{ $users->lastPage() }}
                    </span>

                    <form method="GET" action="{{ route('admin.users.index') }}">
                        @foreach (request()->except('page') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <input type="number" name="page" min="1" max="{{ $users->lastPage() }}"
                            value="{{ $users->currentPage() }}">
                        <button type="submit" class="button secondary">{{ __('messages.go') }}</button>
                    </form>
                </div>
                <div style="margin-top:15px; font-size:14px; color:var(--color-text-subtle);">
                    {{ __('messages.total_records') }}: {{ $users->total() }} |
                    {{ __('messages.current_page') }}: {{ $users->currentPage() }} |
                    {{ __('messages.total_pages') }}: {{ $users->lastPage() }}
                </div>
            @endif
        </div>
    </div>

@endsection
