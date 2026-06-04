@extends('layouts.admin')

@section('title', __('messages.tokens'))

@section('admin_content')
    <div class="container">
        <div class="card">
            <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap;">
                <div>
                    <h1>{{ __('messages.tokens_page_title') }}</h1>
                    <p>{{ __('messages.tokens_page_description') }}</p>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="GET" action="{{ route('admin.tokens.index') }}" style="margin-top:18px; display:flex; gap:8px; flex-wrap:wrap; align-items:center;">
                <input type="text" name="search" placeholder="{{ __('messages.search_token_placeholder') }}" value="{{ request('search') }}" />
                <button type="submit" class="button secondary">{{ __('messages.search') }}</button>
                @if (request('search'))
                    <a href="{{ route('admin.tokens.index') }}" class="button secondary">{{ __('messages.clear') }}</a>
                @endif
            </form>

            @if ($tokens->isEmpty())
                <div class="empty">{{ __('messages.no_tokens_found_admin') }}</div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('messages.label_name') }}</th>
                            <th>{{ __('messages.token') }}</th>
                            <th>{{ __('messages.owner') }}</th>
                            <th>{{ __('messages.status') }}</th>
                            <th>{{ __('messages.created') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tokens as $token)
                            <tr>
                                <td>{{ $token->name }}</td>
                                <td style="font-family: monospace; word-break: break-all;">{{ $token->token }}</td>
                                <td>
                                    {{ $token->user?->name ?? __('messages.unknown') }}<br>
                                    <small style="color:var(--color-text-subtle);">{{ $token->user?->email ?? '' }}</small>
                                </td>
                                <td>
                                    @if ($token->disabled)
                                        <span class="status-badge status-disabled">{{ __('messages.token_status_disabled') }}</span>
                                    @else
                                        <span class="status-badge status-active">{{ __('messages.token_status_active') }}</span>
                                    @endif
                                </td>
                                <td>{{ $token->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div style="margin-top:24px; display:flex; justify-content:flex-end;">
                    {{ $tokens->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
