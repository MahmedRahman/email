@extends('layouts.app')

@section('title', 'لوحة التحكم')
@section('page-title', 'لوحة التحكم')

@section('content')
    <div class="mb-6 rounded-2xl border border-slate-100 bg-gradient-to-l from-blue-50 via-white to-violet-50 p-6 shadow-sm sm:p-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-bold text-slate-800 sm:text-2xl">
                    مرحباً، {{ $user['name'] ?? 'مستخدم' }} 👋
                </h2>
                <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-600">
                    هذه لوحة التحكم لمساعد البريد الشخصي. من هنا يمكنك متابعة الفلاتر النشطة،
                    سير عمل n8n المتصلة، وآخر نشاط للأتمتة.
                </p>
            </div>
            <div class="shrink-0 rounded-xl bg-white/80 px-4 py-3 text-xs text-slate-500 shadow-sm ring-1 ring-slate-100">
                <span class="font-medium text-violet-600">المرحلة 1</span>
                <span class="mx-1">·</span>
                بيانات تجريبية
            </div>
        </div>
    </div>

    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-stat-card label="الفلاتر النشطة" :value="$stats['active_filters']" accent="blue">
            <x-slot:icon>
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card label="سير العمل المتصلة" :value="$stats['connected_workflows']" accent="violet">
            <x-slot:icon>
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card label="رسائل معالجة" :value="number_format($stats['processed_emails'])" accent="emerald">
            <x-slot:icon>
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card label="أتمتة فاشلة" :value="$stats['failed_automations']" accent="rose">
            <x-slot:icon>
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </x-slot:icon>
        </x-stat-card>
    </div>

    <section class="rounded-2xl border border-slate-100 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-5 py-4 sm:px-6">
            <h3 class="text-base font-semibold text-slate-800">آخر نشاط سير العمل</h3>
            <p class="mt-1 text-sm text-slate-500">أحدث تشغيلات الأتمتة المرتبطة بفلاتر البريد</p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/80 text-right">
                        <th class="px-5 py-3 font-medium text-slate-600 sm:px-6">اسم سير العمل</th>
                        <th class="px-5 py-3 font-medium text-slate-600 sm:px-6">المُشغّل</th>
                        <th class="px-5 py-3 font-medium text-slate-600 sm:px-6">الحالة</th>
                        <th class="px-5 py-3 font-medium text-slate-600 sm:px-6">آخر تشغيل</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($recentActivity as $activity)
                        <tr class="transition-colors hover:bg-slate-50/50">
                            <td class="px-5 py-4 font-medium text-slate-800 sm:px-6">{{ $activity['workflow_name'] }}</td>
                            <td class="px-5 py-4 text-slate-600 sm:px-6">{{ $activity['trigger'] }}</td>
                            <td class="px-5 py-4 sm:px-6">
                                <x-status-badge :status="$activity['status']" />
                            </td>
                            <td class="px-5 py-4 text-slate-500 sm:px-6">{{ $activity['last_run'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
