@extends('layouts.app')

@section('title', __('messages.manager_login') ?? 'Manager Login')

@section('styles')
    <style>
        body {
            padding: 32px;
        }

        .container {
            max-width: 420px;
            margin: 0 auto;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="card">
            <h1>{{ __('messages.manager_login') ?? 'Manager Login' }}</h1>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('manager.login.post') }}">
                @csrf

                <div class="field">
                    <label class="label" for="email">Email</label>
                    <input class="input @error('email') is-invalid @enderror" id="email" name="email" type="email"
                        value="{{ old('email') }}" required autofocus />
                    @error('email')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field">
                    <label class="label" for="password">Password</label>
                    <input class="input @error('password') is-invalid @enderror" id="password" name="password" type="password" required
                        autocomplete="current-password" />
                    @error('password')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field">
                    <label>
                        <input type="checkbox" name="remember" /> {{ __('messages.remember_me') ?? 'Remember me' }}
                    </label>
                </div>

                <button class="button" type="submit">{{ __('messages.login') ?? 'Login' }}</button>
            </form>

            <p style="margin-top:18px; color: var(--color-text-muted);">
                Manager accounts are created by admins. If you need access, please ask your administrator.
            </p>
        </div>
    </div>
@endsection
