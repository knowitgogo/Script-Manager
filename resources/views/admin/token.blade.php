@extends('layouts.admin')

@section('title', "Generate Token")

@section('styles')
<style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: ui-sans-serif, system-ui, sans-serif; background: #f8fafc; color: #111827; }
        nav { background: #1e293b; color: white; padding: 16px 32px; display: flex; justify-content: space-between; align-items: center; }
        nav a { color: white; text-decoration: none; padding: 8px 16px; margin: 0 8px; border-radius: 6px; }
        nav a:hover { background: #64748b; }
        nav .logout { background: #2563eb; }
        nav .logout:hover { background: #1d4ed8; }
        .container { max-width: 600px; margin: 32px auto; padding: 0 20px; }
        .card { background: white; border: 1px solid #e5e7eb; border-radius: 12px; padding: 28px; box-shadow: 0 10px 30px rgba(15,23,42,.08); }
        .field { margin-bottom: 18px; }
        .label { display: block; font-weight: 600; margin-bottom: 8px; }
        .input { width: 100%; padding: 12px 14px; border: 1px solid #d1d5db; border-radius: 8px; }
        .button { background: #2563eb; color: white; border: none; padding: 12px 18px; border-radius: 8px; font-weight: 600; cursor: pointer; }
        .button:hover { background: #1d4ed8; }
        .token-display { background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 8px; padding: 16px; margin-top: 18px; word-break: break-all; font-family: monospace; }
    </style>
@endsection

@section('admin_content')

<div class="container">
        <div class="card">
            <h1>Generate Token</h1>

            <form method="POST" action="{{ route('admin.token.generate.post') }}">
                @csrf

                <div class="field">
                    <label class="label" for="name">Token Name</label>
                    <input class="input" id="name" name="name" type="text" placeholder="e.g., API Token" required />
                </div>

                <button class="button" type="submit">Generate Token</button>
            </form>

            @if (session('token'))
                <div class="token-display">
                    <strong>Your Token:</strong><br/>
                    {{ session('token') }}
                </div>
            @endif
        </div>
    </div>

@endsection