@extends('layouts.user')

@section('title', __('messages.generate_token'))

@section('styles')
    <style>
        .container {
            max-width: 600px;
        }

        .token-display {
            background: var(--color-surface-alt);
            border: 1px solid var(--color-border-strong);
            border-radius: 8px;
            padding: 16px;
            margin-top: 18px;
            word-break: break-all;
            font-family: monospace;
        }

        .token-modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .token-modal-backdrop.is-open {
            display: flex;
        }

        .token-modal {
            background: var(--color-surface, #fff);
            color: var(--color-text, #111);
            border-radius: 10px;
            max-width: 420px;
            width: 90%;
            padding: 24px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25);
            text-align: center;
        }

        .token-modal h2 {
            margin: 0 0 12px;
            font-size: 1.2rem;
        }

        .token-modal p {
            margin: 0 0 20px;
            color: var(--color-text-muted, #555);
        }

        .token-modal .button {
            min-width: 100px;
        }
    </style>
@endsection

@section('user_content')

    <div class="container">
        <div class="card">
            <h1>{{ __('messages.generate_token') }}</h1>

            <form method="POST" action="{{ route('token.generate.post') }}">
                @csrf

                <div class="field">
                    <label class="label" for="name">{{ __('messages.create_new_token') }}</label>
                    <input class="input @error('name') is-invalid @enderror" id="name" name="name" type="text"
                        value="{{ old('name') }}" placeholder="{{ __('messages.token_name_placeholder') }}" required />
                    @error('name')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <button class="button" type="submit">{{ __('messages.generate') }}</button>
            </form>

            @if (session('token'))
                <div class="token-display">
                    <strong>{{ __('messages.your_token') ?? 'Your Token:' }}</strong><br />
                    {{ session('token') }}
                </div>
            @endif
        </div>
    </div>

    @if (session('token_name'))
        <div class="token-modal-backdrop is-open" id="tokenModal" role="dialog" aria-modal="true">
            <div class="token-modal">
                <h2>{{ __('messages.generate_token') }}</h2>
                <p>{{ __('messages.token_generated_for', ['name' => session('token_name')]) }}</p>
                <button type="button" class="button" id="closeTokenModal">
                    {{ __('messages.cancel') }}
                </button>
            </div>
        </div>
    @endif

@endsection

@section('scripts')
    <script src="http://localhost:4173/chatbot.iife.js" data-api-url="{{ url('/chat') }}"></script>
    <script>
        (function () {
            const modal = document.getElementById('tokenModal');
            if (!modal) {
                return;
            }

            const closeBtn = document.getElementById('closeTokenModal');

            const close = () => modal.classList.remove('is-open');

            closeBtn.addEventListener('click', close);
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    close();
                }
            });
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    close();
                }
            });
        })();
    </script>
@endsection