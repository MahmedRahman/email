@extends('layouts.app')

@section('title', 'فلاتر البريد')
@section('page-title', 'فلاتر البريد')

@section('content')
    <div class="mb-6 rounded-2xl border border-slate-100 bg-white p-6 shadow-sm sm:p-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h2 class="text-xl font-bold text-slate-800 sm:text-2xl">فلاتر البريد</h2>
                <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-600">
                    قائمة الرسائل المطابقة للفلاتر النشطة مع حالة الرد: في انتظار الرد، تم الرد، أو تجاهل.
                </p>
            </div>
            @if ($statusCounts['all'] > 0)
                <form
                    method="POST"
                    action="{{ route('filters.destroy-all') }}"
                    onsubmit="return confirm('هل تريد حذف جميع الرسائل ({{ $statusCounts['all'] }})؟ لا يمكن التراجع عن هذا الإجراء.');"
                    class="shrink-0"
                >
                    @csrf
                    @method('DELETE')
                    <button
                        type="submit"
                        class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-2.5 text-sm font-medium text-rose-700 transition-colors hover:bg-rose-100"
                    >
                        حذف كل الرسائل
                    </button>
                </form>
            @endif
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 rounded-xl border border-rose-100 bg-rose-50 px-4 py-3 text-sm text-rose-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="mb-6 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
        @php
            $filters = [
                ['key' => 'all', 'label' => 'الكل', 'count' => $statusCounts['all'], 'activeClasses' => 'border-blue-200 bg-blue-50 ring-blue-100', 'countColor' => 'text-blue-700'],
                ['key' => 'waiting_reply', 'label' => 'في انتظار الرد', 'count' => $statusCounts['waiting_reply'], 'activeClasses' => 'border-amber-200 bg-amber-50 ring-amber-100', 'countColor' => 'text-amber-700'],
                ['key' => 'replied', 'label' => 'تم الرد', 'count' => $statusCounts['replied'], 'activeClasses' => 'border-emerald-200 bg-emerald-50 ring-emerald-100', 'countColor' => 'text-emerald-700'],
                ['key' => 'ignored', 'label' => 'تجاهل', 'count' => $statusCounts['ignored'], 'activeClasses' => 'border-slate-200 bg-slate-100 ring-slate-200', 'countColor' => 'text-slate-700'],
            ];
        @endphp

        @foreach ($filters as $filter)
            @php
                $isActive = $activeStatus === $filter['key'];
                $href = $filter['key'] === 'all'
                    ? route('filters.index')
                    : route('filters.index', ['status' => $filter['key']]);
            @endphp
            <a
                href="{{ $href }}"
                @class([
                    'rounded-2xl border p-4 shadow-sm transition-all ring-1',
                    $filter['activeClasses'] => $isActive,
                    'border-slate-100 bg-white ring-slate-100 hover:border-slate-200 hover:bg-slate-50' => ! $isActive,
                ])
            >
                <p class="text-sm font-medium text-slate-600">{{ $filter['label'] }}</p>
                <p @class(['mt-2 text-2xl font-bold', $filter['countColor']])>{{ $filter['count'] }}</p>
                <p class="mt-1 text-xs text-slate-400">رسالة</p>
            </a>
        @endforeach
    </div>

    <section class="rounded-2xl border border-slate-100 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/80 text-right">
                        <th class="whitespace-nowrap px-4 py-3 font-medium text-slate-600 sm:px-6">الحالة</th>
                        <th class="whitespace-nowrap px-4 py-3 font-medium text-slate-600 sm:px-6">التاريخ</th>
                        <th class="whitespace-nowrap px-4 py-3 font-medium text-slate-600 sm:px-6">EmailId</th>
                        <th class="whitespace-nowrap px-4 py-3 font-medium text-slate-600 sm:px-6">From</th>
                        <th class="min-w-[12rem] px-4 py-3 font-medium text-slate-600 sm:px-6">Subject</th>
                        <th class="min-w-[16rem] px-4 py-3 font-medium text-slate-600 sm:px-6">snippet</th>
                        <th class="whitespace-nowrap px-4 py-3 font-medium text-slate-600 sm:px-6">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($emails as $email)
                        <tr class="align-top transition-colors hover:bg-slate-50/50">
                            <td class="whitespace-nowrap px-4 py-4 sm:px-6">
                                <x-email-filter-status-badge :status="$email['status']" />
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 text-slate-500 sm:px-6" dir="ltr">
                                {{ $email['date'] }}
                            </td>
                            <td class="px-4 py-4 sm:px-6">
                                <code class="rounded-md bg-slate-100 px-2 py-1 text-xs text-slate-600" dir="ltr">{{ $email['email_id'] }}</code>
                            </td>
                            <td class="max-w-[14rem] truncate px-4 py-4 font-medium text-slate-800 sm:px-6" title="{{ $email['from'] }}">
                                {{ $email['from'] }}
                            </td>
                            <td class="px-4 py-4 font-medium sm:px-6">
                                <a
                                    href="{{ route('filters.show', $email['id']) }}"
                                    class="text-slate-800 transition-colors hover:text-blue-600"
                                >
                                    {{ $email['subject'] }}
                                </a>
                            </td>
                            <td class="px-4 py-4 text-slate-600 sm:px-6">
                                <p class="line-clamp-2 max-w-xl text-slate-500">{{ $email['snippet'] }}</p>
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 sm:px-6">
                                <div class="flex flex-wrap items-center gap-2">
                                    <a
                                        href="{{ route('filters.show', $email['id']) }}"
                                        class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 transition-colors hover:bg-slate-50"
                                    >
                                        تفاصيل
                                    </a>
                                    <a
                                        href="{{ route('filters.edit', $email['id']) }}"
                                        class="rounded-lg border border-blue-200 bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-700 transition-colors hover:bg-blue-100"
                                    >
                                        تعديل
                                    </a>
                                    @include('partials.email-filter-status-actions', [
                                        'emailId' => $email['id'],
                                        'status' => $email['status'],
                                    ])
                                    <form
                                        method="POST"
                                        action="{{ route('filters.destroy', $email['id']) }}"
                                        onsubmit="return confirm('هل تريد حذف هذه الرسالة؟');"
                                        class="inline"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="rounded-lg border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-medium text-rose-700 transition-colors hover:bg-rose-100"
                                        >
                                            حذف
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                @if ($activeStatus === 'waiting_reply')
                                    لا توجد رسائل في انتظار الرد.
                                @elseif ($activeStatus === 'replied')
                                    لا توجد رسائل تم الرد عليها.
                                @elseif ($activeStatus === 'ignored')
                                    لا توجد رسائل متجاهلة.
                                @else
                                    لا توجد رسائل مطابقة للفلاتر حالياً.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
