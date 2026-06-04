@extends('layouts.app')

@section('title', __('messages.create_account'))

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
            <h1>{{ __('messages.create_account') }}</h1>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('register.post') }}">
                @csrf

                <div class="field">
                    <label class="label" for="name">{{ __('messages.label_name') }}</label>
                    <input class="input @error('name') is-invalid @enderror" id="name" name="name" type="text"
                        value="{{ old('name') }}" required autofocus />
                    @error('name')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field">
                    <label class="label" for="email">{{ __('messages.label_email') }}</label>
                    <input class="input @error('email') is-invalid @enderror" id="email" name="email" type="email"
                        value="{{ old('email') }}" required />
                    @error('email')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field">
                    <label class="label" for="password">{{ __('messages.password') ?? 'Password' }}</label>
                    <input class="input @error('password') is-invalid @enderror" id="password" name="password" type="password" required autocomplete="new-password" />
                    @error('password')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field">
                    <label class="label" for="password_confirmation">{{ __('messages.confirm_password') ?? 'Confirm Password' }}</label>
                    <input class="input @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" />
                    @error('password_confirmation')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <button class="button" type="submit">{{ __('messages.sign_up') ?? 'Sign Up' }}</button>
            </form>

            <p class="helper">{{ __('messages.already_have_account') ?? 'Already have an account?' }} <a href="{{ route('login') }}">{{ __('messages.login_here') ?? 'Login here' }}</a>.</p>
        </div>
    </div>

@endsection
