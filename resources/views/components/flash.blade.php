@if (session('status'))
    <div role="status" class="mb-6 rounded-lg border border-brand-100 bg-brand-50 px-4 py-3 text-sm text-brand-900">
        {{ session('status') }}
    </div>
@endif

@if ($errors->has('delete'))
    <div role="alert" class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
        {{ $errors->first('delete') }}
    </div>
@endif

@if ($errors->any() && ! $errors->has('delete'))
    <div role="alert" class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
        Controleer de gemarkeerde velden: niet alles is goed ingevuld.
    </div>
@endif
