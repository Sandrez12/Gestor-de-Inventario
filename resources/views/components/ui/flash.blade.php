@if (session('success'))
    <div role="status" class="flex items-start gap-3 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
        <span aria-hidden="true">✔</span><p>{{ session('success') }}</p>
    </div>
@endif

@if ($errors->has('stock') || $errors->has('restore'))
    <div role="alert" class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
        {{ $errors->first('stock') ?: $errors->first('restore') }}
    </div>
@endif
