@if($profile->address || $profile->website)
<section class="address-block">
    <div class="card border-0 p-4 profile-card">
        <h2 class="h5 mb-3">Contact</h2>
        @if($profile->address)
            <p class="mb-2"><i class="fas fa-map-marker-alt me-2"></i>{{ $profile->address }}</p>
        @endif
        @if($user->email)
            <p class="mb-2"><i class="fas fa-envelope me-2"></i><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></p>
        @endif
        @if($user->phone)
            <p class="mb-2"><i class="fas fa-phone me-2"></i><a href="tel:{{ $user->phone }}">{{ $user->phone }}</a></p>
        @endif
        @if($profile->website)
            <p class="mb-0"><i class="fas fa-globe me-2"></i><a href="{{ $profile->website }}" target="_blank" rel="noopener">{{ $profile->website }}</a></p>
        @endif
    </div>
</section>
@endif
