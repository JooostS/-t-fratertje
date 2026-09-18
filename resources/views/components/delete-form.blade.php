@props(['action', 'confirm', 'label' => 'Verwijderen'])

<form method="POST" action="{{ $action }}" class="inline"
      onsubmit="return confirm(@js($confirm))">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn-danger btn-sm">{{ $label }}</button>
</form>
