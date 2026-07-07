@extends('layouts.admin')

@section('title', __('messages.edit') . ' ' . __('messages.manager'))

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
            <h1>{{ __('messages.edit') }} {{ __('messages.manager') }}</h1>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('admin.managers.update', $manager) }}">
                @csrf
                @method('PUT')

                <div class="field">
                    <label class="label" for="name">{{ __('messages.label_name') }}</label>
                    <input class="input @error('name') is-invalid @enderror" id="name" name="name" type="text"
                        value="{{ old('name', $manager->name) }}" required autocomplete="name" />
                    @error('name')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field">
                    <label class="label" for="email">{{ __('messages.label_email') }}</label>
                    <input class="input @error('email') is-invalid @enderror" id="email" name="email" type="email"
                        value="{{ old('email', $manager->email) }}" required autocomplete="email" />
                    @error('email')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field">
                    <label class="label" for="password">{{ __('messages.password') ?? 'Password' }}</label>
                    <input class="input @error('password') is-invalid @enderror" id="password" name="password" type="password" autocomplete="new-password" />
                    <small
                        style="color:var(--color-text-muted); display:block; margin-top:6px;">{{ __('messages.leave_blank_password') }}</small>
                    @error('password')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field">
                    <label class="label" for="password_confirmation">{{ __('messages.confirm_password') ?? 'Confirm Password' }}</label>
                    <input class="input @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" type="password"
                        autocomplete="new-password" />
                    @error('password_confirmation')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div style="display:flex; gap: 12px; flex-wrap: wrap; align-items: center;">
                    <button class="button" type="submit">{{ __('messages.save_changes') }}</button>
                    <a href="{{ route('admin.managers.index') }}"
                        class="button secondary">{{ __('messages.back_to_list') }}</a>
                </div>
            </form>
        </div>
    </div>

@endsection
