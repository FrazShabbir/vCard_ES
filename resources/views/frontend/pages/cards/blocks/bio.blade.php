@if($profile->bio)
<section class="bio-block">
    <div class="card border-0 p-4 profile-card">
        <h2 class="h5 mb-3">About</h2>
        <p class="mb-0">{{ $profile->bio }}</p>
    </div>
</section>
@endif
