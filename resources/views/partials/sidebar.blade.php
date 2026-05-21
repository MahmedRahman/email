@php
    $navItems = [
        ['label' => 'لوحة التحكم', 'icon' => 'dashboard', 'route' => 'dashboard'],
        ['label' => 'فلاتر البريد', 'icon' => 'filters', 'route' => 'filters.index', 'route_pattern' => 'filters.'],
        ['label' => 'سير العمل', 'icon' => 'workflows', 'disabled' => true],
        ['label' => 'سجلات الأتمتة', 'icon' => 'logs', 'disabled' => true],
        ['label' => 'الإعدادات', 'icon' => 'settings', 'route' => 'settings.index'],
        ['label' => 'Swagger', 'icon' => 'swagger', 'route' => 'swagger.index', 'new_tab' => true],
    ];
@endphp

<aside
    id="sidebar"
    class="fixed top-0 right-0 z-40 flex h-full w-64 translate-x-full flex-col border-l border-slate-200 bg-white shadow-lg transition-transform duration-300 lg:translate-x-0"
>
    <div class="flex h-16 items-center gap-3 border-b border-slate-100 px-5">
        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-violet-500 text-white shadow-sm">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
        </div>
        <div>
            <p class="text-sm font-semibold text-slate-800">مساعد البريد</p>
            <p class="text-xs text-slate-500">إدارة الفلاتر و n8n</p>
        </div>
    </div>

    <nav class="flex-1 space-y-1 overflow-y-auto p-4">
        @foreach ($navItems as $item)
            @if ($item['disabled'] ?? false)
                <span
                    class="flex cursor-not-allowed items-center gap-3 rounded-xl px-3 py-2.5 text-sm text-slate-400"
                    title="قريباً"
                >
                    @include('partials.sidebar-icon', ['icon' => $item['icon']])
                    {{ $item['label'] }}
                    <span class="mr-auto rounded-md bg-slate-100 px-1.5 py-0.5 text-[10px] font-medium">قريباً</span>
                </span>
            @else
                @php($isActive = request()->routeIs($item['route']) || request()->routeIs(($item['route_pattern'] ?? $item['route']).'*'))
                <a
                    href="{{ route($item['route']) }}"
                    @if ($item['new_tab'] ?? false) target="_blank" rel="noopener noreferrer" @endif
                    @class([
                        'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-colors',
                        'bg-blue-50 text-blue-700' => $isActive,
                        'text-slate-600 hover:bg-slate-50 hover:text-slate-900' => ! $isActive,
                    ])
                >
                    @include('partials.sidebar-icon', ['icon' => $item['icon']])
                    {{ $item['label'] }}
                </a>
            @endif
        @endforeach
    </nav>

    <div class="border-t border-slate-100 p-4">
        <p class="text-center text-xs text-slate-400">المرحلة 1 — واجهة تجريبية</p>
    </div>
</aside>
