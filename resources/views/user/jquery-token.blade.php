@extends('layouts.user')

@section('title', 'Jquery Token Generation')

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
            max-height: 320px;
            overflow: auto;
        }
    </style>
@endsection

@section('user_content')

    <div class="container">
        <div class="card">
            <h1>Jquery Token Generation</h1>
            <p>Enter a token name below. The form sends that value to the API, generates a token, and shows the JSON
                response on the page.</p>

            <form id="jqueryTokenForm" method="POST" action="{{ route('api.tokens.store') }}" onsubmit="return false;">
                @csrf

                <div class="field">
                    <label class="label" for="jquery-name">Token name</label>
                    <input class="input" id="jquery-name" name="name" type="text" value="{{ old('name') }}"
                        placeholder="Type a token name and generate JSON" required />
                </div>

                <button class="button" type="submit" id="generateBtn">
                    {{ __('messages.generate') }}
                </button>
            </form>

            <div id="result" style="display:none;"></div>

            <h3 style="margin-top:24px;">JSON Response</h3>
            <pre id="jsonResponse" class="json-box" style="display:none;"></pre>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function () {

            $('#jqueryTokenForm').on('submit', function (e) {
                e.preventDefault();

                const name = $('#jquery-name').val().trim();
                if (!name) {
                    return;
                }

                const $btn = $('#generateBtn').prop('disabled', true).text('Generating...');
                $('#result').hide();
                $('#jsonResponse').hide();

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
                            $('meta[name="csrf-token"]').attr('content') : $('input[name="_token"]')
                                .val()
                    },
                    success: function (response) {
                        $('#result')
                            .removeClass('result-error')
                            .addClass('result-success')
                            .text(response.message)
                            .show();
                        $('#jsonResponse')
                            .text(JSON.stringify(response, null, 2))
                            .show();
                    },
                    error: function (xhr) {
                        let message = 'Something went wrong.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;
                            message = Object.values(errors).flat().join(' ');
                        }
                        $('#result')
                            .removeClass('result-success')
                            .addClass('result-error')
                            .text(message)
                            .show();
                        $('#jsonResponse')
                            .text(JSON.stringify(xhr.responseJSON ?? {
                                status: xhr.status
                            }, null, 2))
                            .show();
                    },
                    complete: function () {
                        $btn.prop('disabled', false).text('{{ __('messages.generate') }}');
                    }
                });
            });
        })(jQuery);
    </script>
@endsection