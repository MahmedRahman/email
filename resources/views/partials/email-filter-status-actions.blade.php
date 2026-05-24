@props(['emailId', 'status'])

@if ($status !== 'replied')
    <form method="POST" action="{{ route('filters.update-status', $emailId) }}" class="inline">
        @csrf
        <input type="hidden" name="status" value="replied">
        <button type="submit" class="rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-medium text-emerald-700 hover:bg-emerald-100">
            تم الرد
        </button>
    </form>
@endif
@if ($status !== 'waiting_reply')
    <form method="POST" action="{{ route('filters.update-status', $emailId) }}" class="inline">
        @csrf
        <input type="hidden" name="status" value="waiting_reply">
        <button type="submit" class="rounded-lg border border-amber-200 bg-amber-50 px-3 py-1.5 text-xs font-medium text-amber-700 hover:bg-amber-100">
            انتظار الرد
        </button>
    </form>
@endif
@if ($status !== 'ignored')
    <form method="POST" action="{{ route('filters.update-status', $emailId) }}" class="inline">
        @csrf
        <input type="hidden" name="status" value="ignored">
        <button type="submit" class="rounded-lg border border-slate-200 bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-200">
            تجاهل
        </button>
    </form>
@endif
