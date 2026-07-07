@extends('layouts.admin')

@section('title', __('messages.admin_login') ?? 'Admin Login')

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

@section('admin_content')

    <div class="container">
        <div class="card">
            <h1>{{ __('messages.admin_login') ?? 'Admin Login' }}</h1>
            <p style="margin-bottom:18px; color:var(--color-text-muted);">Admins and managers can sign in here.</p>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('admin.login.post') }}">
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

            <p style="margin-top:18px;">
                <a href="{{ route('admin.register') }}">{{ __('messages.create_admin_account') ?? 'Create an admin account' }}</a>
            </p>
        </div>
    </div>

@endsection
