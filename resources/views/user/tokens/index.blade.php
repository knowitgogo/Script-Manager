@extends('layouts.user')

@section('title', __('messages.my_tokens'))

@section('styles')
    <style>
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 24px;
        }

        .table th, .table td {
            padding: 14px 16px;
            text-align: left;
        }

        .table th {
            background: var(--color-surface-alt);
        }

        .empty {
            padding: 24px;
            background: var(--color-surface-alt);
            border-radius: 12px;
            color: var(--color-text-muted);
        }

        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.65);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 50;
        }

        .modal {
            background: var(--color-surface);
            border-radius: 16px;
            max-width: 520px;
            width: 100%;
            padding: 24px;
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.15);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
        }

        .modal-close {
            background: transparent;
            border: none;
            font-size: 24px;
            line-height: 1;
            cursor: pointer;
            color: var(--color-text-muted);
        }

        .button.secondary {
            background: var(--color-surface-alt);
            color: var(--color-text);
        }

        .button.secondary:hover {
            background: var(--color-border);
        }
    </style>
@endsection

@section('user_content')

    <div class="container">
        <div class="card">
            <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap;">
                <div>
                    <h1>{{ __('messages.my_tokens') }}</h1>
                    <p>{{ __('messages.my_tokens') }}</p>
                </div>
                <button type="button" class="button" onclick="openTokenModal()">{{ __('messages.create_new_token') }}</button>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('token'))
                <div class="alert alert-success">
                    <strong>{{ __('messages.new_token_created') }}</strong>
                    <div style="margin-top:8px; word-break: break-all; font-family: monospace;">{{ session('token') }}</div>
                </div>
            @endif

            @php $showGenerate = $showGenerate ?? false; @endphp
            <div id="token-modal" class="modal-overlay" style="display:none;">
                <div class="modal">
                    <div class="modal-header">
                        <h2>{{ __('messages.create_new_token') }}</h2>
                        <button type="button" class="modal-close" onclick="closeTokenModal()">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('token.generate.post') }}">
                            @csrf

                            <div class="field">
                                <label class="label" for="name">{{ __('messages.create_new_token') }}</label>
                                <input class="input @error('name') is-invalid @enderror" id="name" name="name" type="text"
                                    value="{{ old('name') }}"
                                    placeholder="{{ __('messages.token_name_placeholder') }}" required />
                                @error('name')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <button class="button" type="submit">{{ __('messages.generate') }}</button>
                        </form>
                    </div>
                </div>
            </div>

            @if ($tokens->isEmpty())
                <div class="empty">
                    <p>{{ __('messages.no_tokens_found') }}</p>
                </div>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('messages.label_name') }}</th>
                            <th>{{ __('messages.token') }}</th>
                            <th>{{ __('messages.created') }}</th>
                            <th>{{ __('messages.label_actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tokens as $token)
                            <tr>
                                <td>{{ $token->name }}</td>
                                <td style="font-family:monospace;">{{ $token->token }}</td>
                                <td>{{ $token->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a href="{{ route('tokens.edit', $token) }}"
                                        class="button secondary">{{ __('messages.edit') }}</a>
                                    <form method="POST" action="{{ route('tokens.destroy', $token) }}"
                                        style="display:inline-block; margin-left:8px;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="button danger"
                                            style="margin-left:8px;">{{ __('messages.delete') }}</button>
                                    </form>

                                    <button class="button secondary" style="margin-left:8px;"
                                        onclick="navigator.clipboard.writeText('{{ $token->token }}')">{{ __('messages.copy') }}</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div style="margin-top:24px; display:flex; justify-content:flex-end;">
                    {{ $tokens->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
        function openTokenModal() {
            document.getElementById('token-modal').style.display = 'flex';
        }

        function closeTokenModal() {
            document.getElementById('token-modal').style.display = 'none';
        }

        window.addEventListener('click', function(event) {
            if (event.target.id === 'token-modal') {
                closeTokenModal();
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            if ({{ $showGenerate ? 'true' : 'false' }}) {
                openTokenModal();
            }
        });

        document.querySelectorAll('button[onclick^="navigator.clipboard.writeText"]').forEach(button => {
            button.addEventListener('click', function() {
                const token = this.getAttribute('onclick').match(/'([^']+)'/)[1];
                navigator.clipboard.writeText(token).then(() => {
                    alert('{{ __('messages.token_copied') }}');
                }).catch(err => {
                    alert('Failed to copy token: ' + err);
                });
            });
        });
    </script>

@endsection
