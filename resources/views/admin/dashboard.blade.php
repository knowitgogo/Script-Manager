@extends('layouts.admin')

@section('title', __('messages.admin_dashboard'))

@section('styles')
    <style>
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
            margin-top: 32px;
        }

        .card {
            border-radius: 18px;
        }

        .card h2 {
            margin-bottom: 16px;
        }

        .card p {
            margin-bottom: 18px;
        }
    </style>
@endsection

@section('admin_content')
    <div class="container">
        <h1>{{ __('messages.admin_dashboard') }}</h1>

        <div class="grid">
            <div class="card">
                <h2>{{ __('messages.view_status') }}</h2>
                <p>{{ __('messages.view_status') }}</p>
                <a href="{{ route('admin.status') }}" class="button">{{ __('messages.view_status') }}</a>
            </div>

            <div class="card">
                <h2>{{ __('messages.pending_requests') }}</h2>
                <p>{{ __('messages.no_pending_requests') }}</p>
                <a href="{{ route('admin.requests') }}" class="button">{{ __('messages.view_requests') }}</a>
            </div>

            <div class="card">
                <h2>{{ __('messages.users') }}</h2>
                <p>{{ __('messages.total_users') }}: {{ $totalUsers ?? 0 }}</p>
                <a href="{{ route('admin.users.index') }}" class="button">{{ __('messages.view_users') }}</a>
            </div>

            <div class="card">
                <h2>{{ __('messages.generate_token') }}</h2>
                <p>{{ __('messages.generate_token') }}</p>
                <a href="{{ route('admin.token.generate') }}" class="button">{{ __('messages.generate_token') }}</a>
            </div>

            <div class="card">
                <h2>{{ __('messages.tokens') }}</h2>
                <p>{{ __('messages.tokens') }}</p>
                <a href="{{ route('admin.tokens.index') }}" class="button">{{ __('messages.view_tokens') }}</a>
            </div>

            <div class="card">
                <h2>{{ __('messages.activity_summary') }}</h2>
                <p>{{ __('messages.activity_summary_description') }}</p>
                <a href="{{ route('admin.tokens.index') }}" class="button secondary">{{ $totalTokens ?? 0 }} {{ __('messages.tokens') }}</a>
            </div>

            <div class="card">
                <h2>{{ __('messages.create_manager') }}</h2>
                <p>{{ __('messages.create_manager') }}</p>
                <a href="{{ route('admin.managers.create') }}"
                    class="button">{{ __('messages.create_manager_button') }}</a>
            </div>

        </div>
    </div>
@endsection
