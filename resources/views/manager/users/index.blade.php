@extends('layouts.manager')

@section('title', __('messages.manage_users'))

@section('styles')
    <style>
        .manager-users .filter-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
            justify-content: space-between;
            margin: 18px 0;
        }

        .manager-users .button {
            border-radius: 10px;
        }

        .manager-users th,
        .manager-users td {
            text-align: left;
        }

        .footer {
            margin-top: 40px;
            background: var(--color-nav-bg);
            color: #ffffff;
            padding: 16px 0;
            text-align: center;
            border-top: 1px solid var(--color-border);
        }

        .footer .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .footer p {
            margin: 0;
            font-size: 14px;
            opacity: 0.9;
        }
    </style>
@endsection

@section('manager_content')
    <div class="container manager-users">
        <h1>{{ __('messages.users') }}</h1>
        <p>{{ __('messages.users') }} - {{ __('messages.total_records') }}: {{ $users->total() }}</p>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="filter-bar">
            <form method="GET" action="{{ route('manager.users.index') }}"
                style="display:flex; gap:8px; flex-wrap:wrap; align-items:center;">
                <input type="text" name="search" placeholder="{{ __('messages.search') }}..."
                    value="{{ request('search') }}" />
                <button type="submit" class="button secondary">{{ __('messages.search') }}</button>
                @if (request('search'))
                    <a href="{{ route('manager.users.index') }}" class="button secondary">{{ __('messages.clear') }}</a>
                @endif
            </form>
            <div style="color:var(--color-text-muted); font-size:14px;">{{ __('messages.total_records') }}: {{ $users->total() }}</div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>{{ __('messages.label_name') }}</th>
                    <th>{{ __('messages.label_email') }}</th>
                    <th>{{ __('messages.status') }}</th>
                    <th>{{ __('messages.label_actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if ($user->disabled)
                                <span class="status-badge status-disabled">{{ __('messages.disabled') }}</span>
                            @else
                                <span class="status-badge status-active">{{ __('messages.active') }}</span>
                            @endif
                        </td>
                        <td>
                            @if (!$user->disabled)
                                <form method="POST" action="{{ route('manager.users.disable', $user) }}"
                                    style="display:inline;">
                                    @csrf
                                    <button type="submit" class="button">{{ __('messages.disable') }}</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('manager.users.enable', $user) }}"
                                    style="display:inline;">
                                    @csrf
                                    <button type="submit" class="button success">{{ __('messages.enable') }}</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="padding: 20px 12px; text-align: center; color: var(--color-text-muted);">{{ __('messages.no_records') }}</td>
                    </tr>
                @endforelse
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

            <form method="GET" action="{{ route('manager.users.index') }}">
                @foreach (request()->except('page') as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
                <input type="number" name="page" min="1" max="{{ $users->lastPage() }}"
                    value="{{ $users->currentPage() }}">
                <button type="submit" class="button secondary">{{ __('messages.go') }}</button>
            </form>
        </div>
    </div>
@endsection
