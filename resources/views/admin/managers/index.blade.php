@extends('layouts.admin')

@section('title', __('messages.managers'))

@section('admin_content')

    <div class="container">
        <div class="card">
            <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap;">
                <div>
                    <h1>{{ __('messages.managers') }}</h1>
                    <p>{{ __('messages.total_records') }}: {{ $managers->total() }}</p>
                </div>
                <div style="display:flex; gap:8px; flex-wrap:wrap;">
                    <a href="{{ route('admin.managers.create') }}" class="button">{{ __('messages.create_manager_button') }}</a>
                    <a href="{{ route('admin.managers.deleted') }}" class="button secondary">{{ __('messages.show_deleted') }}</a>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div style="margin-top: 18px;">
                <form method="GET" action="{{ route('admin.managers.index') }}"
                    style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
                    <input type="text" name="search" placeholder="{{ __('messages.search') }}..."
                        value="{{ request('search') }}" />
                    <button type="submit" class="button secondary">{{ __('messages.search') }}</button>
                    @if (request('search'))
                        <a href="{{ route('admin.managers.index') }}"
                            class="button secondary">{{ __('messages.clear') }}</a>
                    @endif
                </form>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>{{ __('messages.label_name') }}</th>
                        <th>{{ __('messages.label_email') }}</th>
                        <th>{{ __('messages.created') }}</th>
                        <th>{{ __('messages.label_actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($managers as $manager)
                        <tr>
                            <td>{{ $manager->name }}</td>
                            <td>{{ $manager->email }}</td>
                            <td>{{ $manager->created_at->format('Y-m-d') }}</td>
                            <td>
                                <div style="display:flex; gap:8px; flex-wrap:wrap;">
                                    <a href="{{ route('admin.managers.edit', $manager) }}"
                                        class="button secondary">{{ __('messages.edit') }}</a>
                                    <form method="POST" action="{{ route('admin.managers.destroy', $manager) }}"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="button danger"
                                            onclick="return confirm('{{ __('messages.confirm_delete') }}');">{{ __('messages.delete') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="padding: 20px 12px; text-align: center; color: var(--color-text-muted);">{{ __('messages.no_records') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="pagination-simple">
                @if ($managers->onFirstPage())
                    <span class="disabled">{{ __('messages.previous') }}</span>
                @else
                    <a href="{{ $managers->previousPageUrl() }}">{{ __('messages.previous') }}</a>
                @endif

                @if ($managers->hasMorePages())
                    <a href="{{ $managers->nextPageUrl() }}">{{ __('messages.next') }}</a>
                @else
                    <span class="disabled">{{ __('messages.next') }}</span>
                @endif
            </div>
            <div class="page-jump">
                <span>
                    {{ __('messages.page') }} {{ $managers->currentPage() }} {{ __('messages.of') }} {{ $managers->lastPage() }}
                </span>

                <form method="GET" action="{{ route('admin.managers.index') }}">
                    @foreach (request()->except('page') as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    <input type="number" name="page" min="1" max="{{ $managers->lastPage() }}"
                        value="{{ $managers->currentPage() }}">
                    <button type="submit" class="button secondary">{{ __('messages.go') }}</button>
                </form>
            </div>
        </div>
    </div>

@endsection
