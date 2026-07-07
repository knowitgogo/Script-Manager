@extends('layouts.admin')

@section('title', __('messages.system_status') ?? 'System Status')

@section('styles')
    <style>
        .status-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 0;
            border-bottom: 1px solid var(--color-border);
        }

        .status-item:last-child {
            border-bottom: none;
        }
    </style>
@endsection

@section('admin_content')

    <div class="container">
        <div class="card">
            <h1>{{ __('messages.system_status') ?? 'System Status' }}</h1>

            <div class="status-item">
                <span>{{ __('messages.database_connection') ?? 'Database Connection' }}</span>
                <span class="status-badge status-active">{{ __('messages.active') }}</span>
            </div>

            <div class="status-item">
                <span>{{ __('messages.cache_server') ?? 'Cache Server' }}</span>
                <span class="status-badge status-active">{{ __('messages.active') }}</span>
            </div>

            <div class="status-item">
                <span>{{ __('messages.total_admins') ?? 'Total Admins' }}</span>
                <span>{{ $totalAdmins ?? 0 }}</span>
            </div>

            <div class="status-item">
                <span>{{ __('messages.total_managers') ?? 'Total Managers' }}</span>
                <span>{{ $totalManagers ?? 0 }}</span>
            </div>
            <div class="status-item">
                <span>{{ __('messages.total_users') }}</span>
                <span>{{ $totalUsers ?? 0 }}</span>
            </div>

        </div>
    </div>

@endsection
