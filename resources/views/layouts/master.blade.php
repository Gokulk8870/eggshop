<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Egg Shop ERP</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://unpkg.com/feather-icons"></script>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f9f9f9;
        }

        /* ── Layout ── */
        .page-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: 240px;
            min-height: 100vh;
            background: #ffffff;
            border-right: 1px solid #e8e8e8;
            flex-shrink: 0;
            transition: width 0.3s ease;
            overflow: hidden;
        }

        .sidebar.collapsed {
            width: 60px;
        }

        /* ── Sidebar nav (overrides Bootstrap .nav flex-wrap) ── */
        .sidebar .nav {
            display: flex;
            flex-direction: column;
            flex-wrap: nowrap;
            list-style: none;
            padding: 8px 0;
            margin: 0;
        }

        .sidebar .nav-item {
            display: block;
            width: 100%;
        }

        .sidebar .nav-item a {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            padding: 11px 18px;
            color: #000000;
            text-decoration: none;
            font-size: 14px;
            white-space: nowrap;
        }

        .sidebar .nav-item a:hover {
            background: #f5f5f5;
        }

        .sidebar .nav-item a.active {
            background: #f2f2f2;
        }

        .sidebar .nav-item a svg:not(.arrow svg) {
            width: 16px;
            height: 16px;
            stroke: #000000;
            flex-shrink: 0;
            min-width: 16px;
        }

        /* ── Submenu ── */
        .sub-menu {
            display: none;
            list-style: none;
            padding: 2px 0 4px 18px;
            margin-top: 2px;
            width: 100%;
        }

        .sub-menu.open {
            display: block;
        }

        .sub-menu li {
            margin: 3px 0;
        }

        .sub-menu a {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 4px 6px;
            font-size: 12px;
            font-weight: 400;
            line-height: 1.2;
            color: #000000;
            text-decoration: none;
            white-space: nowrap;
            border-radius: 4px;
        }

        .sub-menu a:hover {
            background: #f5f5f5;
        }

        .sub-menu a.active {
            color: #000;
            font-weight: 500;
            background: #f2f2f2;
        }

        .sub-menu i {
            font-size: 5px;
            margin-right: 6px;
            color: #666666;
            flex-shrink: 0;
        }

        .circle {
            width: 8px;
            height: 8px;
            border: 1.5px solid #9ca3af;
            border-radius: 50%;
            display: inline-block;
            flex-shrink: 0;
        }

        .sub-menu li.active .circle {
            background-color: #4f46e5;
            border-color: #4f46e5;
        }

        .sub-menu li.active a {
            color: #4f46e5;
            font-weight: 500;
        }

        .sub-menu a:hover .circle {
            border-color: #000;
        }

        .arrow {
            display: flex;
            align-items: center;
            margin-left: auto;
            flex-shrink: 0;
            transition: transform 0.3s ease;
        }

        .nav-item.open>.toggle-menu .arrow {
            transform: rotate(180deg);
        }

        .toggle-menu:hover .arrow svg {
            stroke: #000;
        }

        .sidebar.collapsed .sub-menu {
            display: none !important;
        }

        .sidebar.collapsed .arrow {
            display: none;
        }

        .sidebar.collapsed .link-title {
            display: none;
        }

        .sidebar.collapsed .nav-item a {
            justify-content: center;
            padding: 11px 0;
        }

        /* ── Main content ── */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            min-width: 0;
        }

        /* ── Header ── */
        .main-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 20px;
            background: #ffffff;
            border-bottom: 1px solid #e8e8e8;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .menu-toggle {
            display: flex;
            flex-direction: column;
            gap: 4px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
        }

        .menu-toggle span {
            display: block;
            width: 20px;
            height: 2px;
            background: #000000;
            transition: all 0.2s ease;
        }

        .app-name {
            font-size: 16px;
            font-weight: 700;
            color: #000000;
        }

        .header-date {
            font-size: 13px;
            color: #555555;
        }

        /* ── Content & Footer ── */
        .content-area {
            flex: 1;
            padding: 24px;
        }

        .page-header {
            margin-bottom: 20px;
        }

        .page-title {
            font-size: 18px;
            font-weight: 600;
            color: #111111;
            margin: 0 0 4px 0;
        }

        .page-subtitle {
            font-size: 13px;
            color: #666666;
            margin: 0;
        }

        .main-footer {
            padding: 12px 20px;
            background: #ffffff;
            border-top: 1px solid #e8e8e8;
            font-size: 13px;
            color: #555555;
        }
    </style>

    @stack('styles')
</head>

<body>
    <div class="page-wrapper">
        @include('layouts.sidebar')
        <div class="main-content">
            @include('layouts.header')
            <main class="content-area">
                @if (View::hasSection('title'))
                    <div class="page-header">
                        <h4 class="page-title">@yield('title')</h4>

                        @if (View::hasSection('subtitle'))
                            <p class="page-subtitle">@yield('subtitle')</p>
                        @endif
                    </div>
                @endif
                @yield('content')
            </main>
            @include('layouts.footer')
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        feather.replace();
    </script>

    <script>
        document.getElementById('menuToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('collapsed');
        });

        document.querySelectorAll('.toggle-menu').forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const parent = this.parentElement;
                const sub = this.nextElementSibling;
                const isOpen = parent.classList.contains('open');
                parent.classList.toggle('open', !isOpen);
                sub.style.display = isOpen ? 'none' : 'block';
                sub.classList.toggle('open', !isOpen);
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
