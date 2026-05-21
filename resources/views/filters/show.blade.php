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
                <x-email-filter-status-badge :status="$email['status']" />
                <span class="rounded-xl bg-slate-50 px-3 py-1.5 text-xs text-slate-600 ring-1 ring-slate-100" dir="ltr">
                    {{ $email['date'] }}
                </span>
                @if ($email['status'] === 'waiting_reply')
                    <form method="POST" action="{{ route('filters.update-status', $email['id']) }}" class="inline">
                        @csrf
                        <input type="hidden" name="status" value="replied">
                        <button type="submit" class="rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-medium text-emerald-700 hover:bg-emerald-100">
                            تم الرد
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('filters.update-status', $email['id']) }}" class="inline">
                        @csrf
                        <input type="hidden" name="status" value="waiting_reply">
                        <button type="submit" class="rounded-lg border border-amber-200 bg-amber-50 px-3 py-1.5 text-xs font-medium text-amber-700 hover:bg-amber-100">
                            انتظار الرد
                        </button>
                    </form>
                @endif
                <button
                    type="button"
                    id="generate-replies-btn"
                    @disabled(! $hasDeepSeekApiKey)
                    class="inline-flex items-center gap-2 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-medium text-emerald-800 transition-colors hover:bg-emerald-100 disabled:cursor-not-allowed disabled:opacity-60"
                    @if (! $hasDeepSeekApiKey) title="أضف مفتاح DeepSeek من الإعدادات" @endif
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                    </svg>
                    <span id="generate-replies-label">اقتراح ردود</span>
                </button>
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

    <div id="replies-alert" class="mb-6 hidden rounded-xl px-4 py-3 text-sm"></div>

    <section id="suggested-replies" class="mb-6 hidden rounded-2xl border border-emerald-100 bg-white shadow-sm">
        <div class="border-b border-emerald-100 bg-emerald-50/50 px-5 py-4 sm:px-6">
            <h3 class="text-base font-semibold text-emerald-900">اقتراحات الرد</h3>
            <p class="mt-1 text-sm text-emerald-700">ردّان مختلفان بناءً على الرسالة وتعليمات الرد من الإعدادات</p>
        </div>
        <div id="replies-list" class="grid grid-cols-1 gap-4 p-5 sm:grid-cols-2 sm:p-6"></div>
    </section>

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

    <div class="mt-4 flex flex-wrap items-center gap-4">
        <a
            href="{{ route('settings.index') }}"
            class="inline-block text-sm font-medium text-blue-600 hover:text-blue-700"
        >
            تعديل الإعدادات
        </a>
        @unless ($hasDeepSeekApiKey)
            <span class="text-xs text-amber-600">أضف مفتاح DeepSeek من الإعدادات لتفعيل اقتراح الردود</span>
        @endunless
    </div>
@endsection

@push('scripts')
    <script>
        (() => {
            const btn = document.getElementById('generate-replies-btn');
            const label = document.getElementById('generate-replies-label');
            const alertBox = document.getElementById('replies-alert');
            const section = document.getElementById('suggested-replies');
            const list = document.getElementById('replies-list');
            const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
            const url = @json(route('filters.generate-replies', $email['id']));

            const showAlert = (message, type) => {
                alertBox.textContent = message;
                alertBox.className = 'mb-6 rounded-xl px-4 py-3 text-sm ' + (
                    type === 'success'
                        ? 'border border-emerald-100 bg-emerald-50 text-emerald-700'
                        : 'border border-rose-100 bg-rose-50 text-rose-700'
                );
                alertBox.classList.remove('hidden');
            };

            const escapeHtml = (text) => {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            };

            btn?.addEventListener('click', async () => {
                btn.disabled = true;
                label.textContent = 'جاري التحليل...';
                section.classList.add('hidden');
                alertBox.classList.add('hidden');

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                        },
                    });

                    const payload = await response.json();

                    if (!payload.success) {
                        showAlert(payload.message || 'تعذّر إنشاء الردود.', 'error');
                        return;
                    }

                    list.innerHTML = payload.data.replies.map((reply, index) => `
                        <article class="rounded-xl border border-slate-100 bg-slate-50/50 p-4">
                            <div class="mb-3 flex items-start justify-between gap-2">
                                <h4 class="text-sm font-semibold text-slate-800">${escapeHtml(reply.title)}</h4>
                                <span class="shrink-0 rounded-lg bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-800">رد ${index + 1}</span>
                            </div>
                            <p class="whitespace-pre-wrap text-sm leading-relaxed text-slate-700">${escapeHtml(reply.body)}</p>
                            <button
                                type="button"
                                class="copy-reply-btn mt-4 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50"
                            >
                                نسخ الرد
                            </button>
                        </article>
                    `).join('');

                    list.querySelectorAll('.copy-reply-btn').forEach((copyBtn) => {
                        copyBtn.addEventListener('click', () => {
                            const text = copyBtn.closest('article')?.querySelector('p')?.textContent ?? '';

                            navigator.clipboard.writeText(text).then(() => {
                                const original = copyBtn.textContent;
                                copyBtn.textContent = 'تم النسخ';
                                setTimeout(() => { copyBtn.textContent = original; }, 1500);
                            });
                        });
                    });

                    section.classList.remove('hidden');
                    showAlert('تم إنشاء ردّين مقترحين بناءً على تعليمات الرد.', 'success');
                    section.scrollIntoView({ behavior: 'smooth', block: 'start' });
                } catch {
                    showAlert('حدث خطأ أثناء الاتصال بالخادم.', 'error');
                } finally {
                    btn.disabled = false;
                    label.textContent = 'اقتراح ردود';
                }
            });
        })();
    </script>
@endpush
