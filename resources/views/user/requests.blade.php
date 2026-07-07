@extends('layouts.user')

@section('title', __('messages.user_requests_title'))

@section('styles')
    <style>
        .empty {
            padding: 40px 0;
        }
    </style>
@endsection

@section('user_content')

    <div class="container">
        <div class="card">
            <h1>{{ __('messages.user_requests_title') }}</h1>

            <div class="empty">
                <p>{{ __('messages.user_requests_empty') }}</p>
            </div>
        </div>
    </div>

@endsection
