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
                <p class="text-xs text-slate-400">يُحفظ في قاعدة البيانات ويُعرض عبر API</p>
            </div>
        </form>
    </section>
@endsection
