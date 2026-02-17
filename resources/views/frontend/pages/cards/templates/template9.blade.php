<!DOCTYPE html>
<html lang="en">
<head>
    @include('frontend.pages.cards.partials._meta')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Inter',sans-serif; background:#fff; color:#111; min-height:100vh; padding:3rem 1.5rem; max-width:520px; margin:0 auto; }
        .t9-name { font-family:'Cormorant Garamond',serif; font-size:2.5rem; font-weight:700; margin-bottom:.5rem; }
        .t9-role { font-size:.9rem; color:#666; margin-bottom:2rem; letter-spacing:.02em; }
        .t9-divider { width:60px; height:2px; background:#111; margin-bottom:1.5rem; }
        .t9-bio { font-size:.95rem; line-height:1.8; color:#333; margin-bottom:2rem; }
        .t9-meta { font-size:.85rem; color:#555; margin-bottom:1.5rem; }
        .t9-meta a { color:#111; text-decoration:underline; text-underline-offset:3px; }
        .t9-avatar { width:180px; height:180px; border-radius:8px; object-fit:cover; margin-bottom:2rem; display:block; }
        .t9-btn { display:inline-block; padding:.6rem 1.5rem; border:2px solid #111; color:#111; text-decoration:none; font-weight:600; font-size:.9rem; }
        .t9-btn:hover { color:#111; background:#111; color:#fff; }
        .t9-social { margin-top:2rem; display:flex; gap:1rem; flex-wrap:wrap; }
        .t9-social a { color:#333; font-size:1.1rem; }
        .t9-social a:hover { color:#111; }
        .t9-footer { margin-top:3rem; font-size:.75rem; color:#999; }
        .t9-footer a { color:#111; }
        * { box-sizing:border-box; }
        html { -webkit-text-size-adjust:100%; }
        body { overflow-x:hidden; padding:1.5rem 1rem; max-width:100%; }
        img { max-width:100%; height:auto; }
        .t9-btn { min-height:44px; min-width:44px; text-align:center; line-height:1.3; }
        @media (max-width:480px) { .t9-avatar { width:140px; height:140px; } .t9-name { font-size:1.9rem; } }
    </style>
</head>
<body>
    <img src="{{ $avatarUrl ?? asset($profile->avatar ?? 'default/avatar/default.png') }}" alt="{{ $user->full_name }}" class="t9-avatar" loading="lazy">
    <h1 class="t9-name">{{ $user->full_name }}</h1>
    @if($profile->designation || $profile->organization)
        <p class="t9-role">{{ $profile->organization }}{{ $profile->organization && $profile->designation ? ' · ' : '' }}{{ $profile->designation }}</p>
    @endif
    <div class="t9-divider"></div>
    <p class="t9-bio">{{ $profile->bio ?: 'No description yet.' }}</p>
    <div class="t9-meta">
        @if($profile->primaryaddress || $user->address)
            <p><i class="fas fa-map-marker-alt me-1"></i>{{ $profile->primaryaddress ? collect([$profile->primaryaddress->city?->name, $profile->primaryaddress->state?->name, $profile->primaryaddress->country?->name])->filter()->implode(', ') : $user->address }}</p>
        @endif
        @if($user->email)<p><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></p>@endif
    </div>
    <a href="{{ route('downloadVCard', $user->username) }}" class="t9-btn">Save contact</a>
    @include('frontend.pages.cards.partials._nfc')
    @if($profile->socials->count() > 0)
        <div class="t9-social">
            @foreach($profile->socials as $social)
                @php $u = $social->shortlink?->shortlink ?? $social->shortlink?->link; @endphp
                @if($u)<a href="{{ $u }}" target="_blank" rel="noopener noreferrer"><i class="{{ $social->platform->icon ?? 'fas fa-link' }}"></i></a>@endif
            @endforeach
        </div>
    @endif
    @if($profile->customlinks->count() > 0)
        @foreach($profile->customlinks as $link)
            @if($link->shortlink && ($lu = $link->shortlink->shortlink ?? $link->shortlink->link))
                <a href="{{ $lu }}" target="_blank" rel="noopener noreferrer" class="t9-btn d-inline-block mt-2">{{ $link->name ?? 'Link' }}</a>
            @endif
        @endforeach
    @endif
    @include('frontend.pages.cards.partials._shop')
    <div class="t9-footer">@include('frontend.pages.cards.partials._footer')</div>
</body>
</html>
