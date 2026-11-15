@if($profile->socials->count() > 0)
<section class="social-links-block">
    <div class="card border-0 p-4 profile-card">
        <h2 class="h5 mb-3">Connect</h2>
        <div class="d-flex flex-wrap gap-3 justify-content-center">
            @foreach($profile->socials as $social)
                @php $sUrl = $social->shortlink?->shortlink ?? $social->shortlink?->link ?? null; @endphp
                @if($sUrl)
                    <a href="{{ $sUrl }}" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm" aria-label="{{ $social->platform->name ?? 'Link' }}">
                        <i class="{{ $social->platform->icon ?? 'fas fa-link' }}"></i> {{ $social->platform->name ?? 'Link' }}
                    </a>
                @endif
            @endforeach
        </div>
    </div>
</section>
@endif
