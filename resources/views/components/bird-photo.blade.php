@props(['photo', 'eager' => false])

@php($details = config("club.photos.{$photo}"))

<img src="{{ asset('images/birds/'.$details['file']) }}"
     alt="{{ $details['alt'] }}"
     width="{{ $details['width'] }}"
     height="{{ $details['height'] }}"
     @if ($eager) fetchpriority="high" @else loading="lazy" @endif
     {{ $attributes }}>
