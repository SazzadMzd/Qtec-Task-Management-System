<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,700|instrument-sans:400,500,600"
        rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --bg: #f4f5f7;
            --surface: rgba(255, 255, 255, 0.9);
            --surface-strong: #ffffff;
            --ink: #172b4d;
            --muted: #5e6c84;
            --line: rgba(9, 30, 66, 0.12);
            --brand: #0c66e4;
            --brand-deep: #0055cc;
            --brand-soft: #e9f2ff;
            --secondary: #dfe1e6;
            --secondary-ink: #344563;
            --success: #216e4e;
            --success-soft: #dcfff1;
            --danger: #ae2e24;
            --danger-soft: #ffeceb;
            --shadow: 0 18px 48px rgba(9, 30, 66, 0.12);
            --radius-xl: 28px;
            --radius-lg: 20px;
            --radius-md: 14px;
            --radius-sm: 10px;
        }

        * {
            box-sizing: border-box;
        }

        html {
            min-height: 100%;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Instrument Sans", sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at top left, rgba(76, 154, 255, 0.16), transparent 24%),
                radial-gradient(circle at top right, rgba(38, 132, 255, 0.12), transparent 18%),
                linear-gradient(180deg, #f7f8fa 0%, #eef2f7 100%);
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button,
        input,
        select,
        textarea {
            font: inherit;
        }

        .page-shell {
            width: min(1160px, calc(100% - 32px));
            margin: 0 auto;
            padding: 28px 0 48px;
        }

        .hero {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #0c66e4, #1d7afc 58%, #579dff);
            color: #f7fbff;
            border-radius: var(--radius-xl);
            padding: 32px;
            box-shadow: var(--shadow);
        }

        .hero::after {
            content: "";
            position: absolute;
            inset: auto -80px -110px auto;
            width: 280px;
            height: 280px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.24), transparent 65%);
        }

        .hero-row {
            position: relative;
            z-index: 1;
            display: flex;
            gap: 24px;
            align-items: flex-end;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.14);
            font-size: 0.82rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .hero h1 {
            margin: 16px 0 10px;
            font-family: "Space Grotesk", sans-serif;
            font-size: clamp(2rem, 4vw, 3.3rem);
            line-height: 1;
        }

        .hero p {
            max-width: 720px;
            margin: 0;
            color: rgba(247, 251, 255, 0.84);
            font-size: 1rem;
            line-height: 1.7;
        }

        .hero-meta {
            display: grid;
            grid-template-columns: repeat(3, minmax(120px, 1fr));
            gap: 12px;
            width: min(100%, 360px);
        }

        .metric {
            padding: 14px 16px;
            background: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: var(--radius-md);
            backdrop-filter: blur(6px);
        }

        .metric strong {
            display: block;
            font-family: "Space Grotesk", sans-serif;
            font-size: 1.25rem;
            margin-bottom: 4px;
        }

        .metric span {
            color: rgba(255, 248, 241, 0.72);
            font-size: 0.88rem;
        }

        .content-grid {
            display: grid;
            gap: 22px;
            margin-top: 22px;
        }

        .panel {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow);
            backdrop-filter: blur(10px);
        }

        .panel-inner {
            padding: 26px;
        }

        .panel-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 18px;
            margin-bottom: 22px;
            flex-wrap: wrap;
        }

        .panel-title {
            margin: 0;
            font-family: "Space Grotesk", sans-serif;
            font-size: 1.35rem;
        }

        .panel-subtitle {
            margin: 6px 0 0;
            color: var(--muted);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border: 0;
            border-radius: 999px;
            padding: 12px 18px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.18s ease, box-shadow 0.18s ease, background 0.18s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--brand), #1d7afc);
            color: #fff;
            box-shadow: 0 14px 30px rgba(12, 102, 228, 0.24);
        }

        .btn-secondary {
            background: var(--secondary);
            color: var(--secondary-ink);
            border: 1px solid rgba(9, 30, 66, 0.1);
        }

        .btn-danger {
            background: var(--danger-soft);
            color: var(--danger);
            border: 1px solid rgba(157, 47, 47, 0.12);
        }

        .alert {
            margin-bottom: 18px;
            padding: 15px 18px;
            border-radius: var(--radius-md);
            border: 1px solid transparent;
        }

        .alert-success {
            background: var(--success-soft);
            color: var(--success);
            border-color: rgba(37, 107, 74, 0.14);
        }

        .alert-danger {
            background: var(--danger-soft);
            color: var(--danger);
            border-color: rgba(157, 47, 47, 0.12);
        }

        .alert ul {
            margin: 10px 0 0 18px;
            padding: 0;
        }

        .filters {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .filter-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 999px;
            border: 1px solid var(--line);
            color: var(--muted);
            background: rgba(255, 255, 255, 0.7);
            font-weight: 600;
        }

        .filter-pill.active {
            background: var(--brand-soft);
            border-color: rgba(12, 102, 228, 0.22);
            color: var(--brand-deep);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 7px 11px;
            border-radius: 999px;
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }

        .badge-pending {
            background: #fff7d6;
            color: #7f5f01;
        }

        .badge-in-progress {
            background: #deebff;
            color: #0055cc;
        }

        .badge-completed {
            background: #e7fff1;
            color: #1f7a51;
        }

        .task-grid {
            display: grid;
            gap: 16px;
        }

        .task-card {
            display: grid;
            gap: 16px;
            grid-template-columns: minmax(0, 1fr) auto;
            padding: 20px;
            border-radius: var(--radius-lg);
            background: rgba(255, 255, 255, 0.76);
            border: 1px solid rgba(9, 30, 66, 0.08);
        }

        .task-card h3 {
            margin: 0 0 10px;
            font-size: 1.12rem;
            font-family: "Space Grotesk", sans-serif;
        }

        .task-card p {
            margin: 0;
            color: var(--muted);
            line-height: 1.7;
        }

        .task-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 14px;
        }

        .meta-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 10px;
            border-radius: 999px;
            background: #f1f2f4;
            color: var(--muted);
            font-size: 0.85rem;
        }

        .task-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            justify-content: flex-end;
            flex-wrap: wrap;
        }

        .empty-state {
            text-align: center;
            padding: 42px 24px;
            border-radius: var(--radius-lg);
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.88), rgba(233, 242, 255, 0.88));
            border: 1px dashed rgba(12, 102, 228, 0.22);
        }

        .empty-state h3 {
            margin: 12px 0 8px;
            font-family: "Space Grotesk", sans-serif;
            font-size: 1.45rem;
        }

        .empty-state p {
            margin: 0 auto 18px;
            max-width: 520px;
            color: var(--muted);
            line-height: 1.7;
        }

        .form-grid {
            display: grid;
            gap: 18px;
        }

        .field label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .field-note {
            display: block;
            margin-top: 6px;
            color: var(--muted);
            font-size: 0.88rem;
        }

        .control {
            width: 100%;
            border: 1px solid rgba(9, 30, 66, 0.14);
            background: rgba(255, 255, 255, 0.92);
            border-radius: 16px;
            padding: 13px 15px;
            color: var(--ink);
            transition: border-color 0.18s ease, box-shadow 0.18s ease;
        }

        .control:focus {
            outline: none;
            border-color: rgba(12, 102, 228, 0.5);
            box-shadow: 0 0 0 4px rgba(12, 102, 228, 0.12);
        }

        textarea.control {
            min-height: 140px;
            resize: vertical;
        }

        .field-error {
            margin-top: 6px;
            color: var(--danger);
            font-size: 0.88rem;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .swal2-popup {
            border-radius: 22px;
            font-family: "Instrument Sans", sans-serif;
        }

        .swal2-title {
            color: var(--ink);
            font-family: "Space Grotesk", sans-serif;
        }

        .swal2-confirm {
            background: var(--danger) !important;
            box-shadow: none !important;
        }

        .swal2-cancel {
            background: var(--brand) !important;
            color: var(--surface-strong) !important;
            box-shadow: none !important;
        }

        @media (max-width: 780px) {
            .page-shell {
                width: min(100% - 20px, 1160px);
                padding-top: 18px;
            }

            .hero,
            .panel-inner {
                padding: 20px;
            }

            .hero-meta {
                grid-template-columns: 1fr;
                width: 100%;
            }

            .task-card {
                grid-template-columns: 1fr;
            }

            .task-actions {
                justify-content: flex-start;
            }
        }
    </style>
</head>

<body>
    <div class="page-shell">
        <section class="hero">
            <div class="hero-row">
                <div>
                    <span class="eyebrow">Daily Work Control</span>
                    <h1>{{ $heading ?? 'Task Management System' }}</h1>
                    <p>{{ $subheading ?? 'Keep daily work visible, manageable, and easy to update with a focused Laravel task workflow.' }}
                    </p>
                </div>

                @isset($heroMetrics)
                    <div class="hero-meta">
                        @foreach ($heroMetrics as $metric)
                            <div class="metric">
                                <strong>{{ $metric['value'] }}</strong>
                                <span>{{ $metric['label'] }}</span>
                            </div>
                        @endforeach
                    </div>
                @endisset
            </div>
        </section>

        <main class="content-grid">
            <section class="panel">
                <div class="panel-inner">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Please fix the following issues:</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </section>
        </main>
    </div>
    <script>
        document.addEventListener('submit', function(event) {
            const form = event.target;

            if (!(form instanceof HTMLFormElement) || !form.matches('[data-confirm]')) {
                return;
            }

            event.preventDefault();

            const title = form.dataset.confirmTitle || 'Are you sure?';
            const text = form.dataset.confirmText || 'Please confirm this action.';
            const confirmText = form.dataset.confirmButton || 'Confirm';
            const cancelText = form.dataset.cancelButton || 'Cancel';

            Swal.fire({
                title,
                text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: confirmText,
                cancelButtonText: cancelText,
                reverseButtons: true,
                focusCancel: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>
</body>

</html>
