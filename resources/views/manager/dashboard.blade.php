@extends('layouts.manager')

@section('title', __('messages.manager_dashboard_title'))

@section('styles')
    <style>
        .manager-dashboard .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 24px;
            margin-top: 24px;
        }

        .manager-dashboard .card {
            border-radius: 18px;
        }

        .manager-dashboard .card h2 {
            margin-bottom: 14px;
        }

        .manager-dashboard .button {
            border-radius: 10px;
            margin-top: 16px;
        }
    </style>
@endsection

@section('manager_content')
    <div class="container manager-dashboard">
        <h1>{{ __('messages.manager_dashboard_title') }}</h1>
        <p>{{ __('messages.manager_dashboard_intro') }}</p>
        <div class="grid">
            <div class="card">
                <h2>{{ __('messages.users_card_title') }}</h2>
                <p>{{ __('messages.users_card_description') }}</p>
                <a href="{{ route('manager.users.index') }}" class="button">{{ __('messages.manage_users') }}</a>
            </div>

            <div class="card">
                <h2>{{ __('messages.users_at_a_glance') }}</h2>
                <p>{{ __('messages.total_users') }}: <strong>{{ $totalUsers }}</strong></p>
                <p>{{ __('messages.disabled_users_count') }}: <strong>{{ $disabledUsers }}</strong></p>
            </div>
        </div>

        <div class="card" style="margin-top: 24px;">
            <h2>{{ __('messages.disabled_users_title') }}</h2>
            <p>{{ __('messages.disabled_users_description') }}</p>
            <a href="{{ route('manager.users.disabled') }}" class="button">{{ __('messages.view_disabled_users') }}</a>
        </div>

    </div>
@endsection
