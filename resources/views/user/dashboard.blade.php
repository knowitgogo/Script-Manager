@extends('layouts.user')

@section('title', __('messages.user_dashboard'))

@section('styles')
    <style>
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 24px;
            margin-top: 32px;
        }

        .button {
            border-radius: 8px;
        }
    </style>
@endsection

@section('user_content')

    <div class="container">
        <h1>{{ __('messages.user_dashboard') }}</h1>
        <p>{{ __('messages.welcome_back', ['name' => auth()->user()->name]) }}</p>

        <div class="grid">
            <div class="card">
                <h2>{{ __('messages.view_status') }}</h2>
                <p>{{ __('messages.view_status') }}</p>
                <a href="{{ route('status') }}" class="button">{{ __('messages.view_status') }}</a>
            </div>
            <div class="card">
                <h2>{{ __('messages.view_requests') }}</h2>
                <p>{{ __('messages.view_requests') }}</p>
                <a href="{{ route('requests') }}" class="button">{{ __('messages.view_requests') }}</a>
            </div>
            <div class="card">
                <h2>{{ __('messages.my_tokens') }}</h2>
                <p>{{ __('messages.my_tokens') }}</p>
                <a href="{{ route('tokens.index') }}" class="button">{{ __('messages.my_tokens') }}</a>
            </div>
            <div class="card">
                <h2>{{ __('messages.generate_token') }}</h2>
                <p>{{ __('messages.generate_token') }}</p>
                <a href="{{ route('token.generate') }}" class="button">{{ __('messages.generate_token') }}</a>
            </div>
        </div>
    </div>

@endsection
