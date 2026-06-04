@php
    $theme = session('theme', 'auto');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="{{ $theme }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name', 'Laravel'))</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">

    <style>
        :root {
            --color-bg: #f8fafc;
            --color-surface: #ffffff;
            --color-surface-alt: #f1f5f9;
            --color-text: #111827;
            --color-text-muted: #475569;
            --color-text-subtle: #64748b;
            --color-text-strong: #0f172a;
            --color-text-on-primary: #ffffff;
            --color-border: #e5e7eb;
            --color-border-strong: #cbd5e1;
            --color-nav-bg: #1e293b;
            --color-nav-text: #ffffff;
            --color-nav-hover: #64748b;
            --color-primary: #2563eb;
            --color-primary-hover: #1d4ed8;
            --color-secondary: #64748b;
            --color-secondary-hover: #475569;
            --color-success: #16a34a;
            --color-success-hover: #15803d;
            --color-danger: #dc2626;
            --color-danger-hover: #b91c1c;
            --color-disabled: #94a3b8;
            --shadow-card: 0 10px 30px rgba(15, 23, 42, .08);
            --shadow-toggle: 0 4px 14px rgba(0, 0, 0, .25);
        }

        [data-theme="dark"] {
            --color-bg: #0f172a;
            --color-surface: #1e293b;
            --color-surface-alt: #334155;
            --color-text: #f1f5f9;
            --color-text-muted: #cbd5e1;
            --color-text-subtle: #94a3b8;
            --color-text-strong: #f8fafc;
            --color-text-on-primary: #ffffff;
            --color-border: #334155;
            --color-border-strong: #475569;
            --color-nav-bg: #020617;
            --color-nav-text: #f1f5f9;
            --color-nav-hover: #334155;
            --color-primary: #3b82f6;
            --color-primary-hover: #2563eb;
            --color-secondary: #475569;
            --color-secondary-hover: #334155;
            --color-success: #22c55e;
            --color-success-hover: #16a34a;
            --color-danger: #ef4444;
            --color-danger-hover: #dc2626;
            --color-disabled: #64748b;
            --shadow-card: 0 10px 30px rgba(0, 0, 0, .5);
            --shadow-toggle: 0 4px 14px rgba(0, 0, 0, .6);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: ui-sans-serif, system-ui, sans-serif;
            background: var(--color-bg);
            color: var(--color-text);
        }

        h1, h2, h3, h4 {
            color: var(--color-text);
        }

        p {
            color: var(--color-text-muted);
        }

        a {
            color: var(--color-primary);
        }

        .container {
            max-width: 1200px;
            margin: 32px auto;
            padding: 20px;
        }

        .card {
            background: var(--color-surface);
            border: 1px solid var(--color-border);
            border-radius: 12px;
            padding: 28px;
            box-shadow: var(--shadow-card);
        }

        .button {
            background: var(--color-primary);
            color: var(--color-text-on-primary);
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .button:hover {
            background: var(--color-primary-hover);
        }

        .button.secondary {
            background: var(--color-secondary);
        }

        .button.secondary:hover {
            background: var(--color-secondary-hover);
        }

        .button.danger {
            background: var(--color-danger);
        }

        .button.danger:hover {
            background: var(--color-danger-hover);
        }

        .button.success {
            background: var(--color-success);
        }

        .button.success:hover {
            background: var(--color-success-hover);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 14px 12px;
            border-bottom: 1px solid var(--color-border);
            text-align: left;
            color: var(--color-text);
        }

        th {
            background: var(--color-surface-alt);
            color: var(--color-text-strong);
        }

        tr:hover {
            background: var(--color-surface-alt);
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="number"],
        input[type="search"],
        textarea,
        select {
            background: var(--color-surface);
            color: var(--color-text);
            border: 1px solid var(--color-border-strong);
            border-radius: 8px;
            padding: 10px 14px;
        }

        input::placeholder,
        textarea::placeholder {
            color: var(--color-text-subtle);
        }

        .empty {
            text-align: center;
            color: var(--color-text-muted);
            padding: 40px 0;
        }

        .pagination-simple {
            margin-top: 20px;
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            align-items: center;
        }

        .pagination-simple a,
        .pagination-simple span {
            padding: 6px 12px;
            font-size: 13px;
            border: 1px solid var(--color-border-strong);
            border-radius: 6px;
            text-decoration: none;
        }

        .pagination-simple a {
            background: var(--color-surface);
            color: var(--color-text-muted);
        }

        .pagination-simple a:hover {
            background: var(--color-surface-alt);
        }

        .pagination-simple .disabled {
            color: var(--color-disabled);
            background: var(--color-bg);
            cursor: not-allowed;
        }

        .page-jump {
            margin-top: 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            font-size: 14px;
            color: var(--color-text-muted);
        }

        .page-jump form {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .page-jump input {
            width: 90px;
            padding: 8px 10px;
            border: 1px solid var(--color-border-strong);
            border-radius: 8px;
        }

        .admin-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .admin-info div {
            line-height: 1.2;
        }

        .admin-info small {
            display: block;
            opacity: 0.8;
        }

        .message {
            background: var(--color-surface-alt);
            color: var(--color-text);
            border: 1px solid var(--color-border);
            border-radius: 8px;
            padding: 12px 16px;
            margin: 12px 0;
        }

        .form-errors {
            background: rgba(220, 38, 38, .1);
            color: var(--color-danger);
            border: 1px solid var(--color-danger);
            border-radius: 8px;
            padding: 12px 16px;
            margin: 12px 0;
        }

        [data-theme="dark"] .form-errors {
            background: rgba(239, 68, 68, .15);
        }

        .status-active {
            color: var(--color-success);
            font-weight: 600;
        }

        .status-disabled {
            color: var(--color-danger);
            font-weight: 600;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 700;
        }

        .status-badge.status-active {
            background: rgba(22, 163, 74, .15);
            color: var(--color-success);
        }

        .status-badge.status-disabled {
            background: rgba(220, 38, 38, .15);
            color: var(--color-danger);
        }

        .field {
            margin-bottom: 18px;
        }

        .label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--color-text);
        }

        .input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--color-border-strong);
            border-radius: 8px;
            background: var(--color-surface);
            color: var(--color-text);
        }

        .input:focus {
            outline: 2px solid var(--color-primary);
            outline-offset: -1px;
        }

        .input.is-invalid {
            border-color: var(--color-danger);
        }

        .input.is-invalid:focus {
            outline-color: var(--color-danger);
        }

        .field-error {
            display: block;
            margin-top: 6px;
            color: var(--color-danger);
            font-size: 13px;
            font-weight: 500;
        }

        .alert {
            border-radius: 8px;
            padding: 12px 16px;
            margin: 0 0 18px 0;
            border: 1px solid transparent;
            font-weight: 500;
        }

        .alert-success {
            background: rgba(22, 163, 74, .12);
            color: var(--color-success);
            border-color: var(--color-success);
        }

        [data-theme="dark"] .alert-success {
            background: rgba(34, 197, 94, .15);
        }

        .alert-danger {
            background: rgba(220, 38, 38, .1);
            color: var(--color-danger);
            border-color: var(--color-danger);
        }

        [data-theme="dark"] .alert-danger {
            background: rgba(239, 68, 68, .15);
        }

        .theme-toggle {
            position: fixed;
            bottom: 24px;
            right: 24px;
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: var(--color-nav-bg);
            color: var(--color-nav-text);
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-toggle);
            z-index: 1000;
            transition: background .2s, transform .2s;
        }

        .theme-toggle:hover {
            transform: scale(1.05);
        }

        .theme-toggle svg {
            width: 24px;
            height: 24px;
            fill: none;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }
    </style>

    <script>
        (function () {
            var stored = @json($theme);
            var resolved;

            if (stored === 'light' || stored === 'dark') {
                resolved = stored;
            } else {
                resolved = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }

            document.documentElement.setAttribute('data-theme', resolved);
        })();
    </script>

    <script>
        (function () {
            function disableBrowserValidation(form) {
                form.setAttribute('novalidate', 'novalidate');
                form.noValidate = true;
            }

            document.querySelectorAll('form').forEach(disableBrowserValidation);

            var observer = new MutationObserver(function (mutations) {
                mutations.forEach(function (mutation) {
                    mutation.addedNodes.forEach(function (node) {
                        if (node.nodeName === 'FORM') {
                            disableBrowserValidation(node);
                        } else if (node.querySelectorAll) {
                            node.querySelectorAll('form').forEach(disableBrowserValidation);
                        }
                    });
                });
            });

            observer.observe(document.documentElement, { childList: true, subtree: true });
        })();
    </script>

    @yield('layout-styles')
    @yield('styles')
</head>

<body>
    <div class="app-shell">
        @yield('content')
    </div>

    <form method="POST" action="{{ route('theme.update') }}" id="theme-form" style="display:none;">
        @csrf
        <input type="hidden" name="theme" id="theme-input" value="{{ $theme }}">
    </form>

    <button type="button" class="theme-toggle" id="theme-toggle" aria-label="{{ __('messages.theme_toggle') }}" title="{{ __('messages.theme_toggle') }}">
        <svg id="theme-icon-sun" viewBox="0 0 24 24" style="display:none;">
            <circle cx="12" cy="12" r="4"></circle>
            <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"></path>
        </svg>
        <svg id="theme-icon-moon" viewBox="0 0 24 24" style="display:none;">
            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
        </svg>
    </button>

    <script>
        (function () {
            var stored = @json($theme);
            var html = document.documentElement;

            function resolvedTheme() {
                if (stored === 'light' || stored === 'dark') return stored;
                return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }

            function updateIcon() {
                var current = resolvedTheme();
                document.getElementById('theme-icon-sun').style.display = current === 'dark' ? 'block' : 'none';
                document.getElementById('theme-icon-moon').style.display = current === 'dark' ? 'none' : 'block';
            }

            updateIcon();

            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function () {
                if (stored !== 'light' && stored !== 'dark') {
                    var newTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                    html.setAttribute('data-theme', newTheme);
                    updateIcon();
                }
            });

            document.getElementById('theme-toggle').addEventListener('click', function () {
                var current = resolvedTheme();
                var next = current === 'dark' ? 'light' : 'dark';
                document.getElementById('theme-input').value = next;
                document.getElementById('theme-form').submit();
            });
        })();
    </script>
</body>

</html>
