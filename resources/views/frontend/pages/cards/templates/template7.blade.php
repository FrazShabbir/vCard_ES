<!DOCTYPE html>
<html lang="en">
<head>
    @include('frontend.pages.cards.partials._meta')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Syne',sans-serif; min-height:100vh; padding:3rem 1.5rem; background:linear-gradient(135deg,#0f0f0f 0%,#1a1a2e 50%,#16213e 100%); color:#fff; text-align:center; }
        .t7-card { max-width:440px; margin:0 auto; }
        .t7-avatar { width:140px; height:140px; border-radius:50%; object-fit:cover; margin-bottom:1.5rem; border:4px solid #e94560; box-shadow:0 0 40px rgba(233,69,96,.3); }
        .t7-name { font-size:2.2rem; font-weight:800; letter-spacing:-.02em; margin-bottom:.35rem; background:linear-gradient(90deg,#fff,#e94560); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
        .t7-role { font-size:1rem; color:rgba(255,255,255,.7); margin-bottom:1.25rem; }
        .t7-bio { font-size:1rem; line-height:1.7; color:rgba(255,255,255,.85); margin-bottom:1.5rem; }
        .t7-btn { display:inline-flex; align-items:center; gap:.5rem; padding:.75rem 1.75rem; background:#e94560; color:#fff; border-radius:999px; text-decoration:none; font-weight:700; font-size:1rem; }
        .t7-btn:hover { color:#fff; background:#ff6b6b; }
        .t7-social { margin-top:1.5rem; display:flex; justify-content:center; gap:1rem; flex-wrap:wrap; }
        .t7-social a { color:rgba(255,255,255,.7); font-size:1.35rem; }
        .t7-social a:hover { color:#e94560; }
        .t7-footer { margin-top:2rem; font-size:.75rem; color:rgba(255,255,255,.5); }
        .t7-footer a { color:#e94560; text-decoration:none; }
        * { box-sizing:border-box; }
        html { -webkit-text-size-adjust:100%; }
        body { overflow-x:hidden; padding:1.5rem 1rem; }
        img { max-width:100%; height:auto; }
        .t7-card { width:100%; }
        .t7-btn { min-height:44px; min-width:44px; justify-content:center; }
        @media (max-width:480px) { .t7-avatar { width:110px; height:110px; } .t7-name { font-size:1.75rem; } }
    </style>
</head>
<body>
    <div class="t7-card">
        <img src="{{ $avatarUrl ?? asset($profile->avatar ?? 'default/avatar/default.png') }}" alt="{{ $user->full_name }}" class="t7-avatar" loading="lazy">
        <h1 class="t7-name">{{ $user->full_name }}</h1>
        @if($profile->designation || $profile->organization)
            <p class="t7-role">{{ $profile->organization }}{{ $profile->organization && $profile->designation ? ' · ' : '' }}{{ $profile->designation }}</p>
        @endif
        <p class="t7-bio">{{ $profile->bio ?: 'No description yet.' }}</p>
        @if($profile->primaryaddress || $user->address)
            <p class="t7-role"><i class="fas fa-map-marker-alt me-1"></i>{{ $profile->primaryaddress ? collect([$profile->primaryaddress->city?->name, $profile->primaryaddress->state?->name, $profile->primaryaddress->country?->name])->filter()->implode(', ') : $user->address }}</p>
        @endif
        <a href="{{ route('downloadVCard', $user->username) }}" class="t7-btn"><i class="fas fa-download"></i> Save contact</a>
        @include('frontend.pages.cards.partials._nfc')
        @if($profile->socials->count() > 0)
            <div class="t7-social">
                @foreach($profile->socials as $social)
                    @php $u = $social->shortlink?->shortlink ?? $social->shortlink?->link; @endphp
                    @if($u)<a href="{{ $u }}" target="_blank" rel="noopener noreferrer"><i class="{{ $social->platform->icon ?? 'fas fa-link' }}"></i></a>@endif
                @endforeach
            </div>
        @endif
        @if($profile->customlinks->count() > 0)
            @foreach($profile->customlinks as $link)
                @if($link->shortlink && ($lu = $link->shortlink->shortlink ?? $link->shortlink->link))
                    <a href="{{ $lu }}" target="_blank" rel="noopener noreferrer" class="t7-btn d-inline-flex mt-2">{{ $link->name ?? 'Link' }}</a>
                @endif
            @endforeach
        @endif
        @include('frontend.pages.cards.partials._shop')
        <div class="t7-footer">@include('frontend.pages.cards.partials._footer')</div>
    </div>
</body>
</html>
