@extends('layouts.guest')

@section('title', 'تسجيل الدخول')

@section('content')
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="w-full max-w-md">
            <div class="rounded-2xl border border-white/60 bg-white/90 p-8 shadow-lg shadow-slate-200/60 backdrop-blur-sm sm:p-10">
                <div class="mb-8 text-center">
                    <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-500 to-violet-500 text-white shadow-md">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-slate-800">مساعد البريد الشخصي</h1>
                    <p class="mt-2 text-sm leading-relaxed text-slate-500">
                        نظام بسيط لإدارة فلاتر البريد وربطها بسير عمل n8n
                    </p>
                </div>

                @if ($errors->any())
                    <div class="mb-6 rounded-xl border border-rose-100 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.submit') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="mb-1.5 block text-sm font-medium text-slate-700">البريد الإلكتروني</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 outline-none transition-shadow placeholder:text-slate-400 focus:border-blue-400 focus:ring-4 focus:ring-blue-100"
                            placeholder="you@example.com"
                        >
                    </div>

                    <div>
                        <label for="password" class="mb-1.5 block text-sm font-medium text-slate-700">كلمة المرور</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 outline-none transition-shadow placeholder:text-slate-400 focus:border-blue-400 focus:ring-4 focus:ring-blue-100"
                            placeholder="••••••••"
                        >
                    </div>

                    <button
                        type="submit"
                        class="w-full rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-100"
                    >
                        تسجيل الدخول
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <a href="#" class="text-sm text-violet-600 transition-colors hover:text-violet-700">
                        نسيت كلمة المرور؟
                    </a>
                </div>
            </div>

            <p class="mt-6 text-center text-xs text-slate-500">
                بيانات تجريبية: admin@example.com / password
            </p>
        </div>
    </div>
@endsection
