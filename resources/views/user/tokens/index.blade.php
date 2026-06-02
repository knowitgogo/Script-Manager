@extends('layouts.user')

@section('title', 'My Tokens')

@section('styles')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: ui-sans-serif, system-ui, sans-serif;
            background: #f8fafc;
            color: #111827;
        }

        nav {
            background: #1e293b;
            color: white;
            padding: 16px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav a {
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            margin: 0 8px;
            border-radius: 6px;
        }

        nav a:hover {
            background: #64748b;
        }

        .container {
            max-width: 1200px;
            margin: 32px auto;
            padding: 20px;
        }

        .card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 28px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, .08);
        }

        .button {
            background: #2563eb;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .button:hover {
            background: #1d4ed8;
        }

        .secondary {
            background: #e2e8f0;
            color: #0f172a;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 24px;
        }

        .table th,
        .table td {
            padding: 14px 16px;
            border: 1px solid #e2e8f0;
            text-align: left;
        }

        .table th {
            background: #f8fafc;
        }

        .empty {
            padding: 24px;
            background: #f8fafc;
            border-radius: 12px;
        }

        .message {
            margin-bottom: 16px;
            padding: 12px 14px;
            border-radius: 8px;
        }

        .success {
            background: #ecfdf5;
            color: #166534;
            border: 1px solid #bbf7d0;
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
            background: white;
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
            color: #475569;
        }
    </style>
@endsection

@section('user_content')

    <div class="container">
        <div class="card">
            <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap;">
                <div>
                    <h1>My Tokens</h1>
                    <p>View and manage the tokens you've created.</p>
                </div>
                <button type="button" class="button" onclick="openTokenModal()">Create New Token</button>
            </div>

            @if (session('success'))
                <div class="message success">{{ session('success') }}</div>
            @endif

            @if (session('token'))
                <div class="message success">
                    <strong>New token created:</strong>
                    <div style="margin-top:8px; word-break: break-all; font-family: monospace;">{{ session('token') }}</div>
                </div>
            @endif

            @php $showGenerate = $showGenerate ?? false; @endphp
            <div id="token-modal" class="modal-overlay" style="display:none;">
                <div class="modal">
                    <div class="modal-header">
                        <h2>Create Token</h2>
                        <button type="button" class="modal-close" onclick="closeTokenModal()">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('token.generate.post') }}">
                            @csrf

                            <div class="field">
                                <label class="label" for="name">Token Name</label>
                                <input class="input" id="name" name="name" type="text"
                                    placeholder="e.g., My App Token" required />
                            </div>

                            <button class="button" type="submit">Generate Token</button>
                        </form>
                    </div>
                </div>
            </div>

            @if ($tokens->isEmpty())
                <div class="empty">
                    <p>No tokens found yet. Create a token to get started.</p>
                </div>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Token</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tokens as $token)
                            <tr>
                                <td>{{ $token->name }}</td>
                                <td style="font-family:monospace;">{{ $token->token }}</td>
                                <td>{{ $token->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a href="{{ route('tokens.edit', $token) }}" class="button secondary">Edit</a>
                                    <form method="POST" action="{{ route('tokens.destroy', $token) }}"
                                        style="display:inline-block; margin-left:8px;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="button" style="background:#2563eb;">Delete</button>
                                    </form>

                                    <button class="button" style="background:#2563eb; margin-left:8px;"
                                        onclick="navigator.clipboard.writeText('{{ $token->token }}')">Copy</button>
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
                    alert('Token copied to clipboard!');
                }).catch(err => {
                    alert('Failed to copy token: ' + err);
                });
            });
        });
    </script>

@endsection
