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
    <div style="margin-top:15px; font-size:14px; color:#64748b;">
        {{ __('messages.total_records') }}: {{ $tokens->total() }} |
        {{ __('messages.page') }} {{ $tokens->currentPage() }} {{ __('messages.of') }} {{ $tokens->lastPage() }}
    </div>
    <div class="pagination-simple">
        @if ($tokens->onFirstPage())
            <span class="disabled">← {{ __('messages.previous') }}</span>
        @else
            <a href="{{ $tokens->previousPageUrl() }}">{{ __('messages.previous') }}</a>
        @endif

        @if ($tokens->hasMorePages())
            <a href="{{ $tokens->nextPageUrl() }}">{{ __('messages.next') }}</a>
        @else
            <span class="disabled">{{ __('messages.next') }}</span>
        @endif
    </div>
    <div class="page-jump">
        <span>
            {{ __('messages.page') }} {{ $tokens->currentPage() }} {{ __('messages.of') }} {{ $tokens->lastPage() }}
        </span>

        <form method="GET" action="{{ route('tokens.index') }}" class="ajax-page-jump-form">
            @foreach (request()->except('page') as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
            <input type="number" name="page" min="1" max="{{ $tokens->lastPage() }}"
                value="{{ $tokens->currentPage() }}">
            <button type="submit" class="button secondary">{{ __('messages.go') }}</button>
        </form>
    </div>
@endif
