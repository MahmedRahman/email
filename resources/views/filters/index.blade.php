@extends('layouts.app')

@section('title', 'فلاتر البريد')
@section('page-title', 'فلاتر البريد')

@section('content')
    <div class="mb-6 rounded-2xl border border-slate-100 bg-white p-6 shadow-sm sm:p-8">
        <h2 class="text-xl font-bold text-slate-800 sm:text-2xl">فلاتر البريد</h2>
        <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-600">
            قائمة الرسائل المطابقة للفلاتر النشطة. الأعمدة تعكس بيانات Gmail: المعرف، التاريخ، EmailId، المرسل، الموضوع، والمقتطف.
        </p>
        <div class="mt-4 inline-flex items-center gap-2 rounded-xl bg-slate-50 px-3 py-2 text-xs text-slate-500 ring-1 ring-slate-100">
            <span class="font-medium text-blue-600">{{ count($emails) }}</span>
            رسالة
            <span class="text-slate-300">·</span>
            بيانات تجريبية
        </div>
    </div>

    <section class="rounded-2xl border border-slate-100 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/80 text-right">
                        <th class="whitespace-nowrap px-4 py-3 font-medium text-slate-600 sm:px-6">id</th>
                        <th class="whitespace-nowrap px-4 py-3 font-medium text-slate-600 sm:px-6">التاريخ</th>
                        <th class="whitespace-nowrap px-4 py-3 font-medium text-slate-600 sm:px-6">EmailId</th>
                        <th class="whitespace-nowrap px-4 py-3 font-medium text-slate-600 sm:px-6">From</th>
                        <th class="min-w-[12rem] px-4 py-3 font-medium text-slate-600 sm:px-6">Subject</th>
                        <th class="min-w-[16rem] px-4 py-3 font-medium text-slate-600 sm:px-6">snippet</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($emails as $email)
                        <tr class="align-top transition-colors hover:bg-slate-50/50">
                            <td class="px-4 py-4 sm:px-6">
                                <code class="rounded-md bg-slate-100 px-2 py-1 text-xs text-slate-600" dir="ltr">{{ $email['id'] }}</code>
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
                            <td class="px-4 py-4 font-medium text-slate-800 sm:px-6">
                                {{ $email['subject'] }}
                            </td>
                            <td class="px-4 py-4 text-slate-600 sm:px-6">
                                <p class="line-clamp-2 max-w-xl text-slate-500">{{ $email['snippet'] }}</p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                لا توجد رسائل مطابقة للفلاتر حالياً.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
