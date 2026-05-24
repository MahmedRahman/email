@extends('layouts.app')

@section('title', 'تعديل الرسالة')
@section('page-title', 'تعديل الرسالة')

@section('content')
    <div class="mb-6 flex flex-wrap items-center gap-3">
        <a
            href="{{ route('filters.show', $email['id']) }}"
            class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-600 transition-colors hover:text-blue-600"
        >
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
            العودة إلى التفاصيل
        </a>
        <span class="text-slate-300">|</span>
        <a
            href="{{ route('filters.index') }}"
            class="text-sm font-medium text-slate-500 transition-colors hover:text-blue-600"
        >
            فلاتر البريد
        </a>
    </div>

    <div class="mb-6 rounded-2xl border border-slate-100 bg-white p-6 shadow-sm sm:p-8">
        <h2 class="text-xl font-bold text-slate-800 sm:text-2xl">تعديل الرسالة</h2>
        <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-600">
            عدّل بيانات الرسالة المسجّلة في الفلاتر. المعرف الداخلي:
            <code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs" dir="ltr">{{ $email['id'] }}</code>
        </p>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-rose-100 bg-rose-50 px-4 py-3 text-sm text-rose-700">
            <ul class="list-inside list-disc space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="rounded-2xl border border-slate-100 bg-white shadow-sm">
        <form method="POST" action="{{ route('filters.update', $email['id']) }}" class="p-6 sm:p-8">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div>
                    <label for="email_id" class="mb-1.5 block text-sm font-semibold text-slate-800">
                        EmailId
                    </label>
                    <p class="mb-2 text-sm text-slate-500">معرف الرسالة من Gmail أو n8n (يجب أن يكون فريداً).</p>
                    <input
                        type="text"
                        id="email_id"
                        name="email_id"
                        value="{{ old('email_id', $email['email_id']) }}"
                        required
                        dir="ltr"
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition-shadow focus:border-blue-400 focus:ring-4 focus:ring-blue-100 @error('email_id') border-rose-300 focus:border-rose-400 focus:ring-rose-100 @enderror"
                    >
                    @error('email_id')
                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="from" class="mb-1.5 block text-sm font-semibold text-slate-800">
                        From (المرسل)
                    </label>
                    <input
                        type="text"
                        id="from"
                        name="from"
                        value="{{ old('from', $email['from']) }}"
                        required
                        dir="ltr"
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition-shadow focus:border-blue-400 focus:ring-4 focus:ring-blue-100 @error('from') border-rose-300 focus:border-rose-400 focus:ring-rose-100 @enderror"
                    >
                    @error('from')
                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="subject" class="mb-1.5 block text-sm font-semibold text-slate-800">
                        Subject (الموضوع)
                    </label>
                    <input
                        type="text"
                        id="subject"
                        name="subject"
                        value="{{ old('subject', $email['subject']) }}"
                        required
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition-shadow focus:border-blue-400 focus:ring-4 focus:ring-blue-100 @error('subject') border-rose-300 focus:border-rose-400 focus:ring-rose-100 @enderror"
                    >
                    @error('subject')
                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="date" class="mb-1.5 block text-sm font-semibold text-slate-800">
                        التاريخ
                    </label>
                    <p class="mb-2 text-sm text-slate-500">مثال: <span dir="ltr">2026-05-21 09:42</span></p>
                    <input
                        type="text"
                        id="date"
                        name="date"
                        value="{{ old('date', $email['date']) }}"
                        required
                        dir="ltr"
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition-shadow focus:border-blue-400 focus:ring-4 focus:ring-blue-100 @error('date') border-rose-300 focus:border-rose-400 focus:ring-rose-100 @enderror"
                    >
                    @error('date')
                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                @php
                    $selectedStatus = old('status', $email['status']);
                    $statusCards = [
                        [
                            'value' => 'waiting_reply',
                            'label' => 'في انتظار الرد',
                            'hint' => 'الرسالة تحتاج متابعة ورد',
                            'active' => 'peer-checked:border-amber-200 peer-checked:bg-amber-50 peer-checked:ring-amber-100 peer-checked:text-amber-800',
                        ],
                        [
                            'value' => 'replied',
                            'label' => 'تم الرد',
                            'hint' => 'تم الرد على الرسالة',
                            'active' => 'peer-checked:border-emerald-200 peer-checked:bg-emerald-50 peer-checked:ring-emerald-100 peer-checked:text-emerald-800',
                        ],
                        [
                            'value' => 'ignored',
                            'label' => 'تجاهل',
                            'hint' => 'لا حاجة للرد أو المتابعة',
                            'active' => 'peer-checked:border-slate-300 peer-checked:bg-slate-100 peer-checked:ring-slate-200 peer-checked:text-slate-800',
                        ],
                    ];
                @endphp
                <div>
                    <p class="mb-1.5 text-sm font-semibold text-slate-800">الحالة</p>
                    <p class="mb-3 text-sm text-slate-500">اختر حالة الرسالة بالنقر على البطاقة المناسبة.</p>
                    <div
                        class="grid grid-cols-1 gap-3 sm:grid-cols-3 @error('status') rounded-2xl ring-2 ring-rose-200 @enderror"
                        role="radiogroup"
                        aria-label="حالة الرسالة"
                    >
                        @foreach ($statusCards as $card)
                            <label class="block cursor-pointer">
                                <input
                                    type="radio"
                                    name="status"
                                    value="{{ $card['value'] }}"
                                    class="peer sr-only"
                                    @checked($selectedStatus === $card['value'])
                                    required
                                >
                                <div @class([
                                    'rounded-2xl border border-slate-100 bg-white p-4 shadow-sm ring-1 ring-slate-100 transition-all hover:border-slate-200 hover:bg-slate-50',
                                    $card['active'],
                                ])>
                                    <p class="text-sm font-semibold text-slate-700">{{ $card['label'] }}</p>
                                    <p class="mt-1.5 text-xs leading-relaxed text-slate-500">{{ $card['hint'] }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('status')
                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="snippet" class="mb-1.5 block text-sm font-semibold text-slate-800">
                        Snippet (مقتطف)
                    </label>
                    <textarea
                        id="snippet"
                        name="snippet"
                        rows="6"
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm leading-relaxed text-slate-800 outline-none transition-shadow focus:border-blue-400 focus:ring-4 focus:ring-blue-100 @error('snippet') border-rose-300 focus:border-rose-400 focus:ring-rose-100 @enderror"
                    >{{ old('snippet', $email['snippet']) }}</textarea>
                    @error('snippet')
                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex flex-wrap items-center gap-3 border-t border-slate-100 pt-6">
                <button
                    type="submit"
                    class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-medium text-white transition-colors hover:bg-blue-700"
                >
                    حفظ التعديلات
                </button>
                <a
                    href="{{ route('filters.show', $email['id']) }}"
                    class="rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50"
                >
                    إلغاء
                </a>
            </div>
        </form>
    </section>
@endsection
