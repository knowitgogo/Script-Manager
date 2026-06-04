@extends('layouts.admin')

@section('title', __('messages.generate_token'))

@section('styles')
<style>
        .container { max-width: 600px; }
        .token-display {
            background: var(--color-surface-alt);
            border: 1px solid var(--color-border-strong);
            border-radius: 8px;
            padding: 16px;
            margin-top: 18px;
            word-break: break-all;
            font-family: monospace;
        }
    </style>
@endsection

@section('admin_content')

<div class="container">
        <div class="card">
            <h1>{{ __('messages.generate_token') }}</h1>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('admin.token.generate.post') }}">
                @csrf

                <div class="field">
                    <label class="label" for="name">{{ __('messages.token_name') ?? 'Token Name' }}</label>
                    <input class="input @error('name') is-invalid @enderror" id="name" name="name" type="text"
                        value="{{ old('name') }}"
                        placeholder="{{ __('messages.token_name_placeholder') }}" required />
                    @error('name')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <button class="button" type="submit">{{ __('messages.generate') }}</button>
            </form>

            @if (session('token'))
                <div class="token-display">
                    <strong>{{ __('messages.your_token') ?? 'Your Token:' }}</strong><br/>
                    {{ session('token') }}
                </div>
            @endif
        </div>
    </div>

@endsection
