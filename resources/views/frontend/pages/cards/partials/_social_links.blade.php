@if ($profile->socials->count() > 0)
    @foreach ($profile->socials as $social)
        @php $sUrl = $social->shortlink?->shortlink ?? $social->shortlink?->link; @endphp
        @if ($sUrl)
        <a href="{{ $sUrl }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $social->name ?? 'Social' }}"><i class="{{ $social->platform->icon ?? 'fas fa-link' }}"></i></a>
        @endif
    @endforeach
@endif
