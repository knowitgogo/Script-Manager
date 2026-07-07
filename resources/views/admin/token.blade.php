@extends('layouts.admin')

@section('title', __('messages.generate_token'))

@section('styles')
    <style>
        .token-container {
            max-width: 650px;
            margin: 0 auto;
        }

        .token-display {
            margin-top: 20px;
            padding: 16px;
            border-radius: 8px;
            background: var(--color-surface-alt);
            border: 1px solid var(--color-border-strong);
            font-family: monospace;
            word-break: break-all;
            position: relative;
        }

        .copy-btn {
            margin-top: 10px;
        }
    </style>
@endsection

@section('admin_content')

    <div class="container token-container">
        <div class="card">

            <div class="card-header">
                <h1>{{ __('messages.generate_token') }}</h1>
                <p>Create a new API token for external integrations.</p>
            </div>

            <form method="POST" action="{{ route('admin.token.generate.post') }}">
                @csrf

                <div class="field">
                    <label for="name" class="label">
                        {{ __('messages.token_name') ?? 'Token Name' }}
                    </label>

                    <input id="name" name="name" type="text" class="input @error('name') is-invalid @enderror"
                        value="{{ old('name') }}" placeholder="{{ __('messages.token_name_placeholder') }}" required>

                    @error('name')
                        <span class="field-error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <button type="submit" class="button">
                    {{ __('messages.generate') }}
                </button>
            </form>

            @if (session('token'))
                <div class="token-display">
                    @if (session('success'))
                        <div class="alert alert-success" style="margin-bottom:12px;">
                            {{ session('success') }}
                        </div>
                    @endif

                    <strong>
                        {{ __('messages.your_token') ?? 'Generated Token' }}
                    </strong>

                    <hr style="margin:10px 0;">

                    <span id="generated-token">
                        {{ session('token') }}
                    </span>

                    <br>

                    <button type="button" class="button copy-btn" onclick="copyToken()">
                        Copy Token
                    </button>
                </div>


            @endif

        </div>
    </div>

@endsection

@section('scripts')
    <script>
        function copyToken() {
            const token =
                document.getElementById('generated-token').innerText;

            navigator.clipboard.writeText(token)
                .then(() => {
                    alert('Token copied to clipboard!');
                });
        }
    </script>
@endsection
