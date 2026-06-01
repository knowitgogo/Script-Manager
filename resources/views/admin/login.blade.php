<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body { font-family: ui-sans-serif, system-ui, sans-serif; background: #f8fafc; color: #111827; padding: 32px; }
        .container { max-width: 420px; margin: 0 auto; background: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 28px; box-shadow: 0 10px 30px rgba(15,23,42,.08); }
        .field { margin-bottom: 18px; }
        .label { display: block; font-weight: 600; margin-bottom: 8px; }
        .input { width: 100%; padding: 12px 14px; border: 1px solid #d1d5db; border-radius: 8px; }
        .button { background: #2563eb; color: white; border: none; padding: 12px 18px; border-radius: 8px; font-weight: 600; cursor: pointer; }
        .button:hover { background: #1d4ed8; }
        .message { margin-bottom: 16px; padding: 12px 14px; border-radius: 8px; }
        .errors { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .errors li { margin-bottom: 6px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Login</h1>

        @if ($errors->any())
            <div class="message errors">
                <strong>Whoops!</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf

            <div class="field">
                <label class="label" for="email">Email</label>
                <input class="input" id="email" name="email" type="email" value="{{ old('email') }}" required autofocus />
            </div>

            <div class="field">
                <label class="label" for="password">Password</label>
                <input class="input" id="password" name="password" type="password" required autocomplete="current-password" />
            </div>

            <div class="field">
                <label>
                    <input type="checkbox" name="remember" /> Remember me
                </label>
            </div>

            <button class="button" type="submit">Login</button>
        </form>

        <p style="margin-top:18px;">
            <a href="{{ route('admin.register') }}">Create an admin account</a>
        </p>
    </div>
</body>
</html>
