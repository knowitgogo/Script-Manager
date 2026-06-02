@extends('layouts.admin')

@section('title', "Admin Requests")

@section('styles')
<style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: ui-sans-serif, system-ui, sans-serif; background: #f8fafc; color: #111827; }
        nav { background: #1e293b; color: white; padding: 16px 32px; display: flex; justify-content: space-between; align-items: center; }
        nav a { color: white; text-decoration: none; padding: 8px 16px; margin: 0 8px; border-radius: 6px; }
        nav a:hover { background: #64748b; }
        nav .logout { background: #2563eb; }
        nav .logout:hover { background: #1d4ed8; }
        .container { max-width: 1200px; margin: 32px auto; padding: 0 20px; }
        .card { background: white; border: 1px solid #e5e7eb; border-radius: 12px; padding: 28px; box-shadow: 0 10px 30px rgba(15,23,42,.08); }
        .empty { text-align: center; color: #999; padding: 40px 20px; }
    </style>
@endsection

@section('admin_content')

<div class="container">
        <div class="card">
            <h1>Pending Requests</h1>
            <div class="empty">
                <p>No pending requests at this time.</p>
            </div>
        </div>
    </div>

@endsection