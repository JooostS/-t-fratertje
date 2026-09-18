@props(['member'])

@if ($member->trashed())
    <span class="badge bg-stone-200 text-stone-700">Gearchiveerd</span>
@else
    @switch($member->status)
        @case('active')
            <span class="badge bg-brand-100 text-brand-900">Actief</span>
            @break
        @case('quarantine')
            <span class="badge bg-amber-100 text-amber-900">In quarantaine</span>
            @break
        @default
            <span class="badge bg-stone-100 text-stone-600">Inactief</span>
    @endswitch
@endif
