@extends('layouts.user')

@section('title', __('messages.account_status'))

@section('styles')
    <style>
        .container {
            max-width: 900px;
        }

        .status-item {
            margin-top: 16px;
            padding: 16px;
            border: 1px solid var(--color-border);
            border-radius: 10px;
            background: var(--color-surface-alt);
        }
    </style>
@endsection

@section('user_content')

    <div class="container">
        <div class="card">
            <h1>{{ __('messages.account_status') }}</h1>
            <div class="status-item">
                <strong>{{ __('messages.label_name') }}:</strong> {{ auth()->user()->name }}
            </div>
            <div class="status-item">
                <strong>{{ __('messages.label_email') }}:</strong> {{ auth()->user()->email }}
            </div>
            <div class="status-item">
                <strong>{{ __('messages.label_registered') }}:</strong> {{ auth()->user()->created_at->format('Y-m-d') }}
            </div>
            <div class="status-item">
                <strong>{{ __('messages.account_status') }}:</strong>
                @if (auth()->user()->disabled)
                    <span class="status-disabled">{{ __('messages.disabled') }}</span>
                @else
                    <span class="status-active">{{ __('messages.active') }}</span>
                @endif
            </div>
        </div>
        <script src="http://localhost:4173/chatbot.iife.js" data-api-url="{{ url('/chat') }}"></script>

@endsection