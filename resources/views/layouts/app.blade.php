<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name', 'Laravel'))</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">

    @yield('layout-styles')
    @yield('styles')
</head>

<body>
    <div class="app-shell">
        @yield('content')
    </div>
</body>

</html>
