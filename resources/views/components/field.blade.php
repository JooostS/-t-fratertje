@props(['name', 'label', 'type' => 'text', 'value' => null, 'hint' => null])

<div {{ $attributes->only('class') }}>
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}"
           value="{{ old($name, $value) }}"
           {{ $attributes->except('class') }}
           @error($name) aria-invalid="true" @enderror
           class="form-input @error($name) border-red-500 @enderror">
    @if ($hint)
        <p class="mt-1 text-xs text-stone-500">{{ $hint }}</p>
    @endif
    @error($name)
        <p class="form-error">{{ $message }}</p>
    @enderror
</div>
