@extends('layouts.admin')

@section('title', __('messages.admin_requests'))

@section('admin_content')

    <div class="container">
        <div class="card">
            <h1>{{ __('messages.pending_requests') }}</h1>
            <div class="empty">
                <p>{{ __('messages.no_pending_requests') }}</p>
            </div>
        </div>
    </div>

@endsection
