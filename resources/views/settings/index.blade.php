@extends('layouts.app')

@section('title', 'الإعدادات')
@section('page-title', 'الإعدادات')

@section('content')
    <div class="mb-6 rounded-2xl border border-slate-100 bg-white p-6 shadow-sm sm:p-8">
        <h2 class="text-xl font-bold text-slate-800 sm:text-2xl">الإعدادات</h2>
        <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-600">
            إعدادات مساعد البريد: تعليمات الفلاتر وتعليمات الرد لربط Gmail و n8n.
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

            <div class="mb-8 rounded-xl border border-slate-100 bg-slate-50/50 p-5">
                <div class="mb-3 flex flex-wrap items-center justify-between gap-2">
                    <label for="deepseek_api_key" class="text-sm font-semibold text-slate-800">
                        مفتاح DeepSeek API
                    </label>
                    @if ($hasDeepSeekApiKey)
                        <span class="rounded-lg bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700 ring-1 ring-emerald-600/10">
                            مُفعّل
                        </span>
                    @else
                        <span class="rounded-lg bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-700 ring-1 ring-amber-600/10">
                            غير مُعرَّف
                        </span>
                    @endif
                </div>
                <p class="mb-3 text-sm text-slate-500">
                    مطلوب لتنسيق التعليمات بالذكاء الاصطناعي. احصل على المفتاح من
                    <a href="https://platform.deepseek.com/" target="_blank" rel="noopener noreferrer" class="font-medium text-blue-600 hover:text-blue-700">platform.deepseek.com</a>.
                </p>
                <input
                    type="password"
                    id="deepseek_api_key"
                    name="deepseek_api_key"
                    autocomplete="off"
                    dir="ltr"
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition-shadow placeholder:text-slate-400 focus:border-violet-400 focus:ring-4 focus:ring-violet-100 @error('deepseek_api_key') border-rose-300 focus:border-rose-400 focus:ring-rose-100 @enderror"
                    placeholder="{{ $hasDeepSeekApiKey ? '•••••••••••••••• (اتركه فارغاً للإبقاء على المفتاح الحالي)' : 'sk-...' }}"
                >
                @error('deepseek_api_key')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-xs text-slate-400">
                    يُخزَّن مشفّراً في قاعدة البيانات. اترك الحقل فارغاً عند الحفظ إن لم ترد تغيير المفتاح.
                </p>
            </div>

            <div>
                <label for="email_instructions" class="mb-1.5 block text-sm font-semibold text-slate-800">
                    Email instructions
                </label>
                <p class="mb-3 text-sm text-slate-500">
                    اكتب التعليمات التي يجب على النظام اتباعها عند تصنيف ومعالجة البريد الوارد.
                </p>
                <label class="mb-4 inline-flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700">
                    <input
                        type="checkbox"
                        name="email_instructions_enabled"
                        value="1"
                        class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                        @checked(old('email_instructions_enabled', $emailInstructionsEnabled))
                    >
                    <span>تفعيل استخدام Email instructions في الـ workflow</span>
                </label>
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
                <div class="mt-3">
                    <button
                        type="button"
                        class="format-instructions-btn inline-flex items-center gap-2 rounded-xl border border-violet-200 bg-violet-50 px-4 py-2 text-xs font-medium text-violet-800 transition-colors hover:bg-violet-100 disabled:cursor-not-allowed disabled:opacity-60"
                        data-type="email"
                        data-target="email_instructions"
                        data-empty-message="أدخل تعليمات البريد أولاً ثم اضغط التنسيق."
                        data-success-message="تم تنسيق تعليمات البريد. راجع النص ثم احفظ."
                    >
                        تنسيق تعليمات البريد بالذكاء الاصطناعي
                    </button>
                </div>
            </div>

            <div class="mt-8 border-t border-slate-100 pt-8">
                <label for="reply_instructions" class="mb-1.5 block text-sm font-semibold text-slate-800">
                    Reply instructions
                </label>
                <p class="mb-3 text-sm text-slate-500">
                    تعليمات خاصة بصياغة الردود على الرسائل الواردة (النبرة، البنية، متى يُرسل الرد تلقائياً).
                </p>
                <textarea
                    id="reply_instructions"
                    name="reply_instructions"
                    rows="12"
                    required
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm leading-relaxed text-slate-800 outline-none transition-shadow placeholder:text-slate-400 focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100 @error('reply_instructions') border-rose-300 focus:border-rose-400 focus:ring-rose-100 @enderror"
                    placeholder="مثال: استخدم نبرة مهنية، أجب على كل سؤال، اطلب مراجعة بشرية للفواتير..."
                >{{ old('reply_instructions', $replyInstructions) }}</textarea>
                @error('reply_instructions')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
                <div class="mt-3">
                    <button
                        type="button"
                        class="format-instructions-btn inline-flex items-center gap-2 rounded-xl border border-violet-200 bg-violet-50 px-4 py-2 text-xs font-medium text-violet-800 transition-colors hover:bg-violet-100 disabled:cursor-not-allowed disabled:opacity-60"
                        data-type="reply"
                        data-target="reply_instructions"
                        data-empty-message="أدخل تعليمات الرد أولاً ثم اضغط التنسيق."
                        data-success-message="تم تنسيق تعليمات الرد. راجع النص ثم احفظ."
                    >
                        تنسيق تعليمات الرد بالذكاء الاصطناعي
                    </button>
                </div>
            </div>

            <div class="mt-6 flex flex-wrap items-center gap-3 border-t border-slate-100 pt-6">
                <button
                    type="submit"
                    class="rounded-xl bg-gradient-to-l from-blue-600 to-violet-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition-opacity hover:opacity-90"
                >
                    حفظ الإعدادات
                </button>
                <p class="text-xs text-slate-400">التنسيق عبر DeepSeek بعد حفظ المفتاح</p>
            </div>
        </form>
    </section>
@endsection

@push('scripts')
    <script>
        (() => {
            const alertBox = document.getElementById('format-alert');
            const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
            const formatUrl = @json(route('settings.format-instructions'));

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

            document.querySelectorAll('.format-instructions-btn').forEach((btn) => {
                btn.addEventListener('click', async () => {
                    const type = btn.dataset.type;
                    const textarea = document.getElementById(btn.dataset.target);
                    const instructions = textarea?.value.trim() ?? '';

                    if (!instructions) {
                        showAlert(btn.dataset.emptyMessage, 'error');
                        return;
                    }

                    const originalLabel = btn.textContent;
                    btn.disabled = true;
                    btn.textContent = 'جاري التنسيق...';

                    try {
                        const response = await fetch(formatUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrf,
                            },
                            body: JSON.stringify({ instructions, type }),
                        });

                        const payload = await response.json();

                        if (!payload.success) {
                            showAlert(payload.message || 'تعذّر تنسيق التعليمات.', 'error');
                            return;
                        }

                        const field = type === 'reply' ? 'reply_instructions' : 'email_instructions';
                        textarea.value = payload.data[field];
                        showAlert(btn.dataset.successMessage, 'success');
                    } catch {
                        showAlert('حدث خطأ أثناء الاتصال بالخادم.', 'error');
                    } finally {
                        btn.disabled = false;
                        btn.textContent = originalLabel;
                    }
                });
            });
        })();
    </script>
@endpush
