@extends('layouts.user')

@section('title', __('messages.my_tokens'))

@section('styles')
    <style>
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 24px;
        }

        .table th,
        .table td {
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

        .result-success {
            background: rgba(22, 163, 74, .12);
            color: var(--color-success);
            border: 1px solid var(--color-success);
            border-radius: 8px;
            padding: 12px 16px;
            margin-top: 18px;
        }

        .result-error {
            background: rgba(220, 38, 38, .1);
            color: var(--color-danger);
            border: 1px solid var(--color-danger);
            border-radius: 8px;
            padding: 12px 16px;
            margin-top: 18px;
        }

        .json-box {
            background: #0f172a;
            color: #e2e8f0;
            border-radius: 8px;
            padding: 16px;
            margin-top: 18px;
            font-family: monospace;
            font-size: 13px;
            white-space: pre-wrap;
            word-break: break-all;
            max-height: 250px;
            overflow: auto;
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
                <div style="display:flex; gap:12px; align-items:center;">
                    <button type="button" class="button" onclick="openJqueryTokenModal()">{{ __('messages.jquery_token_generation') }}</button>
                    <button type="button" class="button" onclick="openTokenModal()">{{ __('messages.create_new_token') }}</button>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success" style="display:flex; align-items:center; justify-content:space-between; gap:12px;">
                    <span>{{ session('success') }}</span>
                    <button type="button" onclick="this.parentElement.style.display='none'" style="background:transparent; border:none; color:inherit; font-size:20px; cursor:pointer; font-weight:bold; line-height:1;">&times;</button>
                </div>
            @endif

            <!-- Dynamic Success Alert -->
            <div id="jquery-success-alert" class="alert alert-success" style="display:none; align-items:center; justify-content:space-between; gap:12px;">
                <span id="jquery-success-alert-text"></span>
                <button type="button" onclick="closeJquerySuccessAlert()" style="background:transparent; border:none; color:inherit; font-size:20px; cursor:pointer; font-weight:bold; line-height:1;">&times;</button>
            </div>

            @if (session('token_name'))
                <div id="token-generated-popup" class="modal-overlay" style="display:flex;">
                    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="token-generated-title">
                        <div class="modal-header">
                            <h2 id="token-generated-title">{{ __('messages.generate_token') }}</h2>
                            <button type="button" class="modal-close" onclick="closeGeneratedPopup()">&times;</button>
                        </div>
                        <div class="modal-body">
                            <p>
                                {{ __('messages.token_generated_for', ['name' => session('token_name')]) }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Dynamic jQuery Token Generated Popup -->
            <div id="jquery-generated-popup" class="modal-overlay" style="display:none;">
                <div class="modal" role="dialog" aria-modal="true" aria-labelledby="jquery-generated-title">
                    <div class="modal-header">
                        <h2 id="jquery-generated-title">{{ __('messages.generate_token') }}</h2>
                        <button type="button" class="modal-close" onclick="closeJqueryGeneratedPopup()">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p id="jquery-generated-popup-text"></p>
                    </div>
                </div>
            </div>

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
                                <input class="input @error('name') is-invalid @enderror" id="name" name="name"
                                    type="text" value="{{ old('name') }}"
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

            <div id="jquery-token-modal" class="modal-overlay" style="display:none;">
                <div class="modal">
                    <div class="modal-header">
                        <h2>{{ __('messages.jquery_token_generation') }}</h2>
                        <button type="button" class="modal-close" onclick="closeJqueryTokenModal()">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p style="margin-bottom: 16px; font-size: 14px; color: var(--color-text-muted);">
                            Enter a token name below to generate a new token via the API.
                        </p>
                        <form id="jqueryTokenForm" method="POST" action="{{ route('api.tokens.store') }}" onsubmit="return false;">
                            @csrf

                            <div class="field">
                                <label class="label" for="jquery-name">Token name</label>
                                <input class="input" id="jquery-name" name="name" type="text"
                                    placeholder="Type a token name" required />
                            </div>

                            <button class="button" type="submit" id="generateBtn">
                                {{ __('messages.generate') }}
                            </button>
                        </form>

                        <div id="jquery-result" style="display:none;"></div>
                    </div>
                </div>
            </div>

            <div id="tokens-table-container">
                @include('user.tokens.partials.table_and_pagination')
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        let jqueryTokenCreated = false;

        function openTokenModal() {
            document.getElementById('token-modal').style.display = 'flex';
        }

        function closeTokenModal() {
            document.getElementById('token-modal').style.display = 'none';
        }

        function openJqueryTokenModal() {
            jqueryTokenCreated = false;
            document.getElementById('jquery-name').value = '';
            document.getElementById('jquery-result').style.display = 'none';
            document.getElementById('jquery-token-modal').style.display = 'flex';
        }

        function closeJqueryTokenModal() {
            document.getElementById('jquery-token-modal').style.display = 'none';
            if (jqueryTokenCreated) {
                location.reload();
            }
        }

        function closeGeneratedPopup() {
            const popup = document.getElementById('token-generated-popup');
            if (popup) {
                popup.style.display = 'none';
            }
        }

        function closeJqueryGeneratedPopup() {
            const popup = document.getElementById('jquery-generated-popup');
            if (popup) {
                popup.style.display = 'none';
            }
        }

        function closeJquerySuccessAlert() {
            const alertEl = document.getElementById('jquery-success-alert');
            if (alertEl) {
                alertEl.style.display = 'none';
            }
        }

        window.addEventListener('click', function(event) {
            if (event.target.id === 'token-modal') {
                closeTokenModal();
            }
            if (event.target.id === 'jquery-token-modal') {
                closeJqueryTokenModal();
            }
            if (event.target.id === 'token-generated-popup') {
                closeGeneratedPopup();
            }
            if (event.target.id === 'jquery-generated-popup') {
                closeJqueryGeneratedPopup();
            }
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeGeneratedPopup();
                closeJqueryGeneratedPopup();
                closeTokenModal();
                closeJqueryTokenModal();
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            if ({{ $showGenerate ? 'true' : 'false' }}) {
                openTokenModal();
            }

            const pendingMsg = sessionStorage.getItem('jquery_success_message');
            if (pendingMsg) {
                sessionStorage.removeItem('jquery_success_message');
                
                // Show dynamic success banner
                const alertEl = document.getElementById('jquery-success-alert');
                const textEl = document.getElementById('jquery-success-alert-text');
                if (alertEl && textEl) {
                    textEl.textContent = pendingMsg;
                    alertEl.style.display = 'flex';
                }

                // Show dynamic success popup
                const popupEl = document.getElementById('jquery-generated-popup');
                const popupTextEl = document.getElementById('jquery-generated-popup-text');
                if (popupEl && popupTextEl) {
                    popupTextEl.textContent = pendingMsg;
                    popupEl.style.display = 'flex';
                }
            }
        });

        $(document).on('click', 'button[onclick^="navigator.clipboard.writeText"]', function(e) {
            e.preventDefault();
            const token = this.getAttribute('onclick').match(/'([^']+)'/)[1];
            navigator.clipboard.writeText(token).then(() => {
                alert('{{ __('messages.token_copied') }}');
            }).catch(err => {
                alert('Failed to copy token: ' + err);
            });
        });

        $(document).on('click', '#tokens-table-container .pagination-simple a', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            if (url) {
                fetchPage(url);
            }
        });

        $(document).on('submit', '#tokens-table-container .page-jump form', function(e) {
            e.preventDefault();
            const $form = $(this);
            const url = $form.attr('action');
            const data = $form.serialize();
            fetchPage(url + '?' + data);
        });

        function fetchPage(url, push = true) {
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    if (response && response.html) {
                        $('#tokens-table-container').html(response.html);
                        if (push) {
                            window.history.pushState({ path: url }, '', url);
                        }
                    }
                },
                error: function(xhr) {
                    console.error('Failed to load page:', xhr);
                }
            });
        }

        window.addEventListener('popstate', function(event) {
            fetchPage(window.location.href, false);
        });

        $(document).ready(function() {
            $('#jqueryTokenForm').on('submit', function(e) {
                e.preventDefault();

                const name = $('#jquery-name').val().trim();
                if (!name) {
                    return;
                }

                const $btn = $('#generateBtn').prop('disabled', true).text('Generating...');
                $('#jquery-result').hide();

                $.ajax({
                    url: '{{ route('api.tokens.store') }}',
                    type: 'POST',
                    contentType: 'application/json',
                    dataType: 'json',
                    data: JSON.stringify({
                        name: name
                    }),
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').length ?
                            $('meta[name="csrf-token"]').attr('content') : $('input[name="_token"]').val()
                    },
                    success: function(response) {
                        jqueryTokenCreated = true;
                        
                        const messageTemplate = '{{ __('messages.token_generated_for', ['name' => ':name']) }}';
                        const successMessage = messageTemplate.replace(':name', response.data.name);

                        sessionStorage.setItem('jquery_success_message', successMessage);
                        closeJqueryTokenModal();
                    },
                    error: function(xhr) {
                        let message = 'Something went wrong.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;
                            message = Object.values(errors).flat().join(' ');
                        }
                        $('#jquery-result')
                            .removeClass('result-success')
                            .addClass('result-error')
                            .text(message)
                            .show();
                    },
                    complete: function() {
                        $btn.prop('disabled', false).text('{{ __('messages.generate') }}');
                    }
                });
            });
        });
    </script>

@endsection
