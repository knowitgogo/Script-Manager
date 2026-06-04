@extends('layouts.user')

@section('title', __('messages.edit') . ' ' . __('messages.token'))

@section('styles')
<style>
        .container { max-width: 600px; }
        .token-value {
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

@section('user_content')

<div class="container">
        <div class="card">
            <h1>{{ __('messages.edit') }} {{ __('messages.token') }}</h1>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('tokens.update', $token) }}">
                @csrf
                @method('PUT')

                <div class="field">
                    <label class="label" for="name">{{ __('messages.label_name') }}</label>
                    <input class="input" id="name" name="name" type="text" value="{{ old('name', $token->name) }}" required />
                </div>

                <button type="submit" class="button">{{ __('messages.save_changes') }}</button>
                <a href="{{ route('tokens.index') }}" class="button secondary" style="margin-left:12px;">{{ __('messages.cancel') ?? 'Cancel' }}</a>
            </form>

            <div class="token-value">
                <strong>{{ __('messages.token') }}</strong><br />
                {{ $token->token }}
            </div>
        </div>
    </div>

@endsection
