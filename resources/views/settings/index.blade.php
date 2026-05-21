@extends('layouts.app')

@section('title', 'الإعدادات')
@section('page-title', 'الإعدادات')

@section('content')
    <div class="mb-6 rounded-2xl border border-slate-100 bg-white p-6 shadow-sm sm:p-8">
        <h2 class="text-xl font-bold text-slate-800 sm:text-2xl">الإعدادات</h2>
        <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-600">
            إعدادات مساعد البريد. تُستخدم تعليمات البريد لتوجيه الفلاتر والأتمتة عند ربط Gmail و n8n.
        </p>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <div id="format-alert" class="mb-6 hidden rounded-xl px-4 py-3 text-sm"></div>

    <section class="rounded-2xl border border-slate-100 bg-white shadow-sm">
        <form method="POST" action="{{ route('settings.update') }}" class="p-6 sm:p-8">
            @csrf

            <div>
                <label for="email_instructions" class="mb-1.5 block text-sm font-semibold text-slate-800">
                    Email instructions
                </label>
                <p class="mb-3 text-sm text-slate-500">
                    اكتب التعليمات التي يجب على النظام اتباعها عند تصنيف ومعالجة البريد الوارد.
                </p>
                <textarea
                    id="email_instructions"
                    name="email_instructions"
                    rows="12"
                    required
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm leading-relaxed text-slate-800 outline-none transition-shadow placeholder:text-slate-400 focus:border-blue-400 focus:ring-4 focus:ring-blue-100 @error('email_instructions') border-rose-300 focus:border-rose-400 focus:ring-rose-100 @enderror"
                    placeholder="مثال: صنّف الفواتير كعاجلة، وأرشف النشرات الإخبارية..."
                >{{ old('email_instructions', $emailInstructions) }}</textarea>
                @error('email_instructions')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6 flex flex-wrap items-center gap-3 border-t border-slate-100 pt-6">
                <button
                    type="submit"
                    class="rounded-xl bg-gradient-to-l from-blue-600 to-violet-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition-opacity hover:opacity-90"
                >
                    حفظ الإعدادات
                </button>
                <button
                    type="button"
                    id="format-instructions-btn"
                    class="inline-flex items-center gap-2 rounded-xl border border-violet-200 bg-violet-50 px-5 py-2.5 text-sm font-medium text-violet-800 transition-colors hover:bg-violet-100 disabled:cursor-not-allowed disabled:opacity-60"
                >
                    <svg id="format-instructions-icon" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                    </svg>
                    <span id="format-instructions-label">تنسيق التعليمات بالذكاء الاصطناعي</span>
                </button>
                <p class="text-xs text-slate-400">يُحفظ في قاعدة البيانات · التنسيق عبر DeepSeek</p>
            </div>
        </form>
    </section>
@endsection

@push('scripts')
    <script>
        (() => {
            const btn = document.getElementById('format-instructions-btn');
            const label = document.getElementById('format-instructions-label');
            const textarea = document.getElementById('email_instructions');
            const alertBox = document.getElementById('format-alert');
            const csrf = document.querySelector('meta[name="csrf-token"]')?.content;

            const showAlert = (message, type) => {
                alertBox.textContent = message;
                alertBox.className = 'mb-6 rounded-xl px-4 py-3 text-sm ' + (
                    type === 'success'
                        ? 'border border-emerald-100 bg-emerald-50 text-emerald-700'
                        : 'border border-rose-100 bg-rose-50 text-rose-700'
                );
                alertBox.classList.remove('hidden');
                alertBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            };

            btn?.addEventListener('click', async () => {
                const instructions = textarea.value.trim();

                if (!instructions) {
                    showAlert('أدخل تعليمات البريد أولاً ثم اضغط التنسيق.', 'error');
                    return;
                }

                btn.disabled = true;
                label.textContent = 'جاري التنسيق...';

                try {
                    const response = await fetch(@json(route('settings.format-instructions')), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                        },
                        body: JSON.stringify({ email_instructions: instructions }),
                    });

                    const payload = await response.json();

                    if (!payload.success) {
                        showAlert(payload.message || 'تعذّر تنسيق التعليمات.', 'error');
                        return;
                    }

                    textarea.value = payload.data.email_instructions;
                    showAlert('تم تنسيق التعليمات. راجع النص ثم اضغط «حفظ الإعدادات».', 'success');
                } catch {
                    showAlert('حدث خطأ أثناء الاتصال بالخادم.', 'error');
                } finally {
                    btn.disabled = false;
                    label.textContent = 'تنسيق التعليمات بالذكاء الاصطناعي';
                }
            });
        })();
    </script>
@endpush
