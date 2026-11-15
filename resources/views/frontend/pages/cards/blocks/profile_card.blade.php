<section class="profile-card-block text-center py-4">
    <div class="card border-0 p-4 profile-card">
        <img src="{{ $avatarUrl }}" alt="{{ $user->first_name }}" class="rounded-circle mx-auto mb-3" style="width: 180px; height: 180px; object-fit: cover;">
        <h1 class="h3 mb-1">{{ $user->first_name }} {{ $user->last_name }}</h1>
        @if($profile->designation)
            <p class="text-muted mb-1">{{ $profile->designation }}</p>
        @endif
        @if($profile->organization)
            <p class="text-muted small mb-0">{{ $profile->organization }}</p>
        @endif
    </div>
</section>
