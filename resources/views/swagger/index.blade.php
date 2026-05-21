@extends('layouts.app')

@section('title', 'Swagger')
@section('page-title', 'Swagger')

@section('content')
    <div class="mb-4 rounded-2xl border border-slate-100 bg-white px-5 py-4 shadow-sm sm:px-6">
        <h2 class="text-lg font-semibold text-slate-800">Swagger — Email Filter API</h2>
        <p class="mt-1 text-sm text-slate-500">
            وثائق واختبار واجهات
            <code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs" dir="ltr">GET /api/email-filters/information</code>
            و
            <code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs" dir="ltr">GET /api/email-filters/{email_id}</code>
        </p>
    </div>

    <section class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
        <div id="swagger-ui" class="min-h-[calc(100vh-12rem)]"></div>
    </section>
@endsection

@push('scripts')
    <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@5.11.0/swagger-ui.css">
    <script src="https://unpkg.com/swagger-ui-dist@5.11.0/swagger-ui-bundle.js"></script>
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            SwaggerUIBundle({
                url: @json($openApiUrl),
                dom_id: '#swagger-ui',
                deepLinking: true,
                persistAuthorization: true,
                tryItOutEnabled: true,
                requestInterceptor: (request) => {
                    request.credentials = 'include';
                    return request;
                },
            });
        });
    </script>
@endpush
