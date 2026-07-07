@extends('layouts.admin')

@section('title', __('messages.create_user'))

@section('styles')
    <style>
        .container {
            max-width: 540px;
        }
    </style>
@endsection

@section('admin_content')

    <div class="container">
        <div class="card">
            <h1>{{ __('messages.create_user') }}</h1>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf

                <div class="field">
                    <label class="label" for="name">{{ __('messages.label_name') }}</label>
                    <input class="input @error('name') is-invalid @enderror" id="name" name="name" type="text"
                        value="{{ old('name') }}" required autocomplete="name" />
                    @error('name')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field">
                    <label class="label" for="email">{{ __('messages.label_email') }}</label>
                    <input class="input @error('email') is-invalid @enderror" id="email" name="email" type="email"
                        value="{{ old('email') }}" required autocomplete="email" />
                    @error('email')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field">
                    <label class="label" for="password">{{ __('messages.password') ?? 'Password' }}</label>
                    <input class="input @error('password') is-invalid @enderror" id="password" name="password" type="password" required
                        autocomplete="new-password" />
                    @error('password')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field">
                    <label class="label" for="password_confirmation">{{ __('messages.confirm_password') ?? 'Confirm Password' }}</label>
                    <input class="input @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" type="password" required
                        autocomplete="new-password" />
                    @error('password_confirmation')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <button class="button" type="submit">{{ __('messages.create_user_button') }}</button>
            </form>
        </div>
    </div>

@endsection
