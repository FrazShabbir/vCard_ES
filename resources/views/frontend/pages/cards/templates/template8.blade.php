<!DOCTYPE html>
<html lang="en">
<head>
    @include('frontend.pages.cards.partials._meta')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Plus Jakarta Sans',sans-serif; background:#f0f4f8; color:#1a1a2e; min-height:100vh; padding:2rem 1rem; }
        .t8-container { max-width:400px; margin:0 auto; }
        .t8-card { background:#fff; border-radius:16px; padding:1.5rem; margin-bottom:12px; box-shadow:0 4px 20px rgba(0,0,0,.04); }
        .t8-hero { text-align:center; padding-bottom:1rem; }
        .t8-avatar { width:88px; height:88px; border-radius:50%; object-fit:cover; margin-bottom:.75rem; }
        .t8-name { font-size:1.35rem; font-weight:700; margin-bottom:.2rem; }
        .t8-role { font-size:.85rem; color:#64748b; margin-bottom:1rem; }
        .t8-bio { font-size:.875rem; line-height:1.65; color:#475569; }
        .t8-card a.t8-btn { display:flex; align-items:center; justify-content:center; gap:.5rem; padding:.7rem 1rem; background:#1a1a2e; color:#fff; border-radius:12px; text-decoration:none; font-weight:600; font-size:.9rem; }
        .t8-card a.t8-btn:hover { color:#fff; background:#2d2d44; }
        .t8-card a.t8-btn.outline { background:transparent; color:#1a1a2e; border:2px solid #e2e8f0; }
        .t8-card a.t8-btn.outline:hover { border-color:#1a1a2e; }
        .t8-social { display:flex; gap:10px; flex-wrap:wrap; justify-content:center; }
        .t8-social a { width:44px; height:44px; border-radius:12px; background:#f1f5f9; color:#475569; display:flex; align-items:center; justify-content:center; text-decoration:none; }
        .t8-social a:hover { background:#1a1a2e; color:#fff; }
        .t8-footer { text-align:center; padding:1rem; font-size:.7rem; color:#94a3b8; }
        .t8-footer a { color:#1a1a2e; }
        * { box-sizing:border-box; }
        html { -webkit-text-size-adjust:100%; }
        body { overflow-x:hidden; }
        img { max-width:100%; height:auto; }
        .t8-container { width:100%; max-width:100%; padding:0 .5rem; }
        .t8-card a.t8-btn { min-height:44px; min-width:44px; }
        @media (max-width:480px) { .t8-avatar { width:72px; height:72px; } .t8-name { font-size:1.2rem; } }
    </style>
</head>
<body>
    <div class="t8-container">
        <div class="t8-card t8-hero">
            <img src="{{ $avatarUrl ?? asset($profile->avatar ?? 'default/avatar/default.png') }}" alt="{{ $user->full_name }}" class="t8-avatar" loading="lazy">
            <h1 class="t8-name">{{ $user->full_name }}</h1>
            @if($profile->designation || $profile->organization)
                <p class="t8-role">{{ $profile->organization }}{{ $profile->organization && $profile->designation ? ' · ' : '' }}{{ $profile->designation }}</p>
            @endif
            <p class="t8-bio">{{ $profile->bio ?: 'No description yet.' }}</p>
        </div>
        <div class="t8-card">
            <a href="{{ route('downloadVCard', $user->username) }}" class="t8-btn"><i class="fas fa-download"></i> Save contact</a>
            @include('frontend.pages.cards.partials._nfc')
        </div>
        @if($profile->primaryaddress || $user->address || $user->email)
        <div class="t8-card">
            @if($profile->primaryaddress || $user->address)
                <p class="t8-bio mb-2"><i class="fas fa-map-marker-alt me-1"></i>{{ $profile->primaryaddress ? collect([$profile->primaryaddress->city?->name, $profile->primaryaddress->state?->name, $profile->primaryaddress->country?->name])->filter()->implode(', ') : $user->address }}</p>
            @endif
            @if($user->email)<p class="t8-bio mb-0"><a href="mailto:{{ $user->email }}" class="t8-btn outline">{{ $user->email }}</a></p>@endif
        </div>
        @endif
        @if($profile->socials->count() > 0)
        <div class="t8-card">
            <div class="t8-social">
                @foreach($profile->socials as $social)
                    @php $u = $social->shortlink?->shortlink ?? $social->shortlink?->link; @endphp
                    @if($u)<a href="{{ $u }}" target="_blank" rel="noopener noreferrer"><i class="{{ $social->platform->icon ?? 'fas fa-link' }}"></i></a>@endif
                @endforeach
            </div>
        </div>
        @endif
        @if($profile->customlinks->count() > 0)
            @foreach($profile->customlinks as $link)
                @if($link->shortlink && ($lu = $link->shortlink->shortlink ?? $link->shortlink->link))
                <div class="t8-card">
                    <a href="{{ $lu }}" target="_blank" rel="noopener noreferrer" class="t8-btn outline"><i class="fas fa-external-link-alt me-1"></i>{{ $link->name ?? 'Link' }}</a>
                </div>
                @endif
            @endforeach
        @endif
        @include('frontend.pages.cards.partials._shop')
        <div class="t8-footer">@include('frontend.pages.cards.partials._footer')</div>
    </div>
</body>
</html>
