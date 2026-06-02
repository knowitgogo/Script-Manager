@extends('layouts.user')

@section('title', "User Requests")

@section('styles')
<style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: ui-sans-serif, system-ui, sans-serif; background: #f8fafc; color: #111827; }
        nav { background: #1e293b; color: white; padding: 16px 32px; display: flex; justify-content: space-between; align-items: center; }
        nav a { color: white; text-decoration: none; padding: 8px 16px; margin: 0 8px; border-radius: 6px; }
        nav a:hover { background: #64748b; }
        .container { max-width: 900px; margin: 32px auto; padding: 20px; }
        .card { background: white; border: 1px solid #e5e7eb; border-radius: 12px; padding: 28px; box-shadow: 0 10px 30px rgba(15,23,42,.08); }
        .button { background: #2563eb; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block; }
        .button:hover { background: #1d4ed8; }
        .user-info { display: flex; flex-direction: column; gap: 4px; }
        .user-info span { opacity: 0.85; }
        .empty { text-align: center; color: #475569; padding: 40px 0; }
    </style>
@endsection

@section('user_content')

<div class="container">
        <div class="card">
            <h1>Requests</h1>
            <div class="empty">
                <p>There are currently no requests to display.</p>
            </div>
        </div>
    </div>

@endsection