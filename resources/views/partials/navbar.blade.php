@php
    $authUser = session('auth.user', ['name' => 'مستخدم', 'email' => '']);
    $initial = mb_substr($authUser['name'] ?? 'م', 0, 1);
@endphp

<header class="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-slate-200 bg-white/90 px-4 backdrop-blur sm:px-6">
    <div class="flex items-center gap-3">
        <button
            type="button"
            id="sidebar-toggle"
            class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition-colors hover:bg-slate-50 lg:hidden"
            aria-label="فتح القائمة"
        >
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        <div class="hidden sm:block">
            <h1 class="text-base font-semibold text-slate-800">@yield('page-title', 'لوحة التحكم')</h1>
            <p class="text-xs text-slate-500">نظرة عامة على فلاتر البريد وأتمتة n8n</p>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <div class="hidden text-left sm:block">
            <p class="text-sm font-medium text-slate-800">{{ $authUser['name'] }}</p>
            <p class="text-xs text-slate-500">{{ $authUser['email'] }}</p>
        </div>
        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-violet-500 text-sm font-semibold text-white shadow-sm">
            {{ $initial }}
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button
                type="submit"
                class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-medium text-slate-600 transition-colors hover:bg-slate-50 hover:text-slate-900"
            >
                خروج
            </button>
        </form>
    </div>
</header>
