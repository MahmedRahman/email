<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة التحكم') — {{ config('app.name', 'مساعد البريد') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-700 antialiased">
    <div id="sidebar-overlay" class="fixed inset-0 z-30 bg-slate-900/40 opacity-0 pointer-events-none transition-opacity lg:hidden" aria-hidden="true"></div>

    @include('partials.sidebar')

    <div class="flex min-h-screen flex-col lg:mr-64">
        @include('partials.navbar')

        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
