@extends('layouts.app')

@section('title', __('messages.login') ?? 'Login')

@section('styles')
<style>
        body { padding: 32px; }
        .container { max-width: 420px; margin: 0 auto; }
        .helper { margin-top: 16px; font-size: 14px; color: var(--color-text-muted); }
        .helper a { color: var(--color-primary); text-decoration: none; }
    </style>
@endsection

@section('content')

<div class="container">
        <div class="card">
            <h1>{{ __('messages.login') ?? 'Login' }}</h1>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                <div class="field">
                    <label class="label" for="email">{{ __('messages.label_email') }}</label>
                    <input class="input @error('email') is-invalid @enderror" id="email" name="email" type="email"
                        value="{{ old('email') }}" required autofocus />
                    @error('email')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field">
                    <label class="label" for="password">{{ __('messages.password') ?? 'Password' }}</label>
                    <input class="input @error('password') is-invalid @enderror" id="password" name="password" type="password" required autocomplete="current-password" />
                    @error('password')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <button class="button" type="submit">{{ __('messages.login') ?? 'Login' }}</button>
            </form>

            <p class="helper">{{ __('messages.dont_have_account') ?? "Don't have an account?" }} <a href="{{ route('register') }}">{{ __('messages.register_here') ?? 'Register here' }}</a>.</p>
        </div>
    </div>

@endsection
