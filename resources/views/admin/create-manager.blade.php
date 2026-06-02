@extends('layouts.admin')

@section('title', "Create Manager")

@section('styles')
<style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: ui-sans-serif, system-ui, sans-serif; background: #f8fafc; color: #111827; }
        nav { background: #1e293b; color: white; padding: 16px 32px; display: flex; justify-content: space-between; align-items: center; }
        nav a { color: white; text-decoration: none; padding: 8px 16px; margin: 0 8px; border-radius: 6px; }
        nav a:hover { background: #64748b; }
        nav .logout { background: #2563eb; }
        nav .logout:hover { background: #1d4ed8; }
        .container { max-width: 540px; margin: 32px auto; padding: 20px; }
        .card { background: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 28px; box-shadow: 0 10px 30px rgba(15,23,42,.08); }
        .field { margin-bottom: 18px; }
        .label { display: block; font-weight: 600; margin-bottom: 8px; }
        .input { width: 100%; padding: 12px 14px; border: 1px solid #d1d5db; border-radius: 8px; }
        .button { background: #2563eb; color: white; border: none; padding: 12px 18px; border-radius: 8px; font-weight: 600; cursor: pointer; }
        .button:hover { background: #1d4ed8; }
        .message { margin-bottom: 16px; padding: 12px 14px; border-radius: 8px; }
        .success { background: #ecfdf5; color: #166534; border: 1px solid #bbf7d0; }
        .errors { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .errors li { margin-bottom: 6px; }
    </style>
@endsection

@section('admin_content')

<div class="container">
        <div class="card">
            <h1>Create Manager</h1>

            @if (session('success'))
                <div class="message success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="message errors">
                    <strong>There are some problems with your submission:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.managers.store') }}">
                @csrf

                <div class="field">
                    <label class="label" for="name">Name</label>
                    <input class="input" id="name" name="name" type="text" value="{{ old('name') }}" required autocomplete="name" />
                </div>

                <div class="field">
                    <label class="label" for="email">Email</label>
                    <input class="input" id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" />
                </div>

                <div class="field">
                    <label class="label" for="password">Password</label>
                    <input class="input" id="password" name="password" type="password" required autocomplete="new-password" />
                </div>

                <div class="field">
                    <label class="label" for="password_confirmation">Confirm Password</label>
                    <input class="input" id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" />
                </div>

                <button class="button" type="submit">Create Manager</button>
            </form>
        </div>
    </div>

@endsection