@extends('layouts.app')

@section('title', 'تفاصيل الرسالة')
@section('page-title', 'تفاصيل الرسالة')

@section('content')
    <div class="mb-6">
        <a
            href="{{ route('filters.index') }}"
            class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-600 transition-colors hover:text-blue-600"
        >
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
            العودة إلى فلاتر البريد
        </a>
    </div>

    <div class="mb-6 rounded-2xl border border-slate-100 bg-white p-6 shadow-sm sm:p-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div class="min-w-0 flex-1">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">الموضوع</p>
                <h2 class="mt-1 text-xl font-bold text-slate-800 sm:text-2xl">{{ $email['subject'] }}</h2>
                <p class="mt-2 text-sm text-slate-500" dir="ltr">{{ $email['from'] }}</p>
            </div>
            <div class="flex shrink-0 flex-wrap items-center gap-2">
                <span class="rounded-xl bg-slate-50 px-3 py-1.5 text-xs text-slate-600 ring-1 ring-slate-100" dir="ltr">
                    {{ $email['date'] }}
                </span>
                <form
                    method="POST"
                    action="{{ route('filters.destroy', $email['id']) }}"
                    onsubmit="return confirm('هل تريد حذف هذه الرسالة؟');"
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
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <section class="rounded-2xl border border-slate-100 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4 sm:px-6">
                <h3 class="text-base font-semibold text-slate-800">بيانات الرسالة</h3>
            </div>
            <dl class="divide-y divide-slate-100 px-5 sm:px-6">
                <div class="grid gap-1 py-4 sm:grid-cols-3">
                    <dt class="text-sm font-medium text-slate-500">id</dt>
                    <dd class="sm:col-span-2">
                        <code class="rounded-md bg-slate-100 px-2 py-1 text-xs text-slate-700" dir="ltr">{{ $email['id'] }}</code>
                    </dd>
                </div>
                <div class="grid gap-1 py-4 sm:grid-cols-3">
                    <dt class="text-sm font-medium text-slate-500">EmailId</dt>
                    <dd class="sm:col-span-2">
                        <code class="rounded-md bg-slate-100 px-2 py-1 text-xs text-slate-700" dir="ltr">{{ $email['email_id'] }}</code>
                    </dd>
                </div>
                <div class="grid gap-1 py-4 sm:grid-cols-3">
                    <dt class="text-sm font-medium text-slate-500">From</dt>
                    <dd class="text-sm text-slate-800 sm:col-span-2" dir="ltr">{{ $email['from'] }}</dd>
                </div>
                <div class="grid gap-1 py-4 sm:grid-cols-3">
                    <dt class="text-sm font-medium text-slate-500">التاريخ</dt>
                    <dd class="text-sm text-slate-800 sm:col-span-2" dir="ltr">{{ $email['date'] }}</dd>
                </div>
                <div class="grid gap-1 py-4 sm:grid-cols-3">
                    <dt class="text-sm font-medium text-slate-500">Subject</dt>
                    <dd class="text-sm font-medium text-slate-800 sm:col-span-2">{{ $email['subject'] }}</dd>
                </div>
            </dl>
        </section>

        <section class="rounded-2xl border border-slate-100 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4 sm:px-6">
                <h3 class="text-base font-semibold text-slate-800">snippet</h3>
                <p class="mt-1 text-sm text-slate-500">مقتطف نص الرسالة</p>
            </div>
            <div class="px-5 py-5 sm:px-6">
                <p class="whitespace-pre-wrap text-sm leading-relaxed text-slate-700">{{ $email['snippet'] ?: '—' }}</p>
            </div>
        </section>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
        <section class="rounded-2xl border border-slate-100 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4 sm:px-6">
                <h3 class="text-base font-semibold text-slate-800">Email instructions</h3>
                <p class="mt-1 text-sm text-slate-500">تعليمات التصنيف والمعالجة</p>
            </div>
            <div class="px-5 py-5 sm:px-6">
                <p class="whitespace-pre-wrap text-sm leading-relaxed text-slate-700">{{ $emailInstructions ?: '—' }}</p>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-100 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4 sm:px-6">
                <h3 class="text-base font-semibold text-slate-800">Reply instructions</h3>
                <p class="mt-1 text-sm text-slate-500">تعليمات الرد على البريد</p>
            </div>
            <div class="px-5 py-5 sm:px-6">
                <p class="whitespace-pre-wrap text-sm leading-relaxed text-slate-700">{{ $replyInstructions ?: '—' }}</p>
            </div>
        </section>
    </div>

    <div class="mt-4">
        <a
            href="{{ route('settings.index') }}"
            class="inline-block text-sm font-medium text-blue-600 hover:text-blue-700"
        >
            تعديل الإعدادات
        </a>
    </div>
@endsection
