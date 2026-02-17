<!DOCTYPE html>
<html lang="en">
<head>
    @include('frontend.pages.cards.partials._meta')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Outfit',sans-serif; min-height:100vh; display:flex; align-items:center; justify-content:center; padding:2rem; background:linear-gradient(160deg,#667eea 0%,#764ba2 50%,#f093fb 100%); }
        .t5-card { max-width:380px; width:100%; background:rgba(255,255,255,.15); backdrop-filter:blur(20px); border-radius:24px; border:1px solid rgba(255,255,255,.2); padding:2rem; text-align:center; color:#fff; }
        .t5-avatar { width:100px; height:100px; border-radius:50%; object-fit:cover; border:3px solid rgba(255,255,255,.5); margin-bottom:1rem; }
        .t5-name { font-size:1.5rem; font-weight:700; margin-bottom:.25rem; }
        .t5-role { opacity:.9; font-size:.9rem; margin-bottom:1rem; }
        .t5-bio { font-size:.85rem; line-height:1.6; opacity:.95; margin-bottom:1.25rem; }
        .t5-btn { display:inline-flex; align-items:center; gap:.4rem; padding:.6rem 1.25rem; background:#fff; color:#667eea; border-radius:12px; text-decoration:none; font-weight:600; font-size:.9rem; }
        .t5-btn:hover { color:#764ba2; }
        .t5-social { margin-top:1.25rem; display:flex; justify-content:center; gap:.75rem; flex-wrap:wrap; }
        .t5-social a { color:rgba(255,255,255,.9); font-size:1.2rem; }
        .t5-social a:hover { color:#fff; }
        .t5-footer { margin-top:1.5rem; font-size:.7rem; opacity:.8; }
        .t5-footer a { color:#fff; text-decoration:underline; }
        * { box-sizing:border-box; }
        html { -webkit-text-size-adjust:100%; }
        body { overflow-x:hidden; padding:1rem; }
        img { max-width:100%; height:auto; }
        .t5-card { width:100%; }
        .t5-btn { min-height:44px; min-width:44px; justify-content:center; }
        @media (max-width:480px) { .t5-avatar { width:80px; height:80px; } .t5-name { font-size:1.25rem; } }
    </style>
</head>
<body>
    <div class="t5-card">
        <img src="{{ $avatarUrl ?? asset($profile->avatar ?? 'default/avatar/default.png') }}" alt="{{ $user->full_name }}" class="t5-avatar" loading="lazy">
        <h1 class="t5-name">{{ $user->full_name }}</h1>
        @if($profile->designation || $profile->organization)
            <p class="t5-role">{{ $profile->organization }}{{ $profile->organization && $profile->designation ? ' · ' : '' }}{{ $profile->designation }}</p>
        @endif
        <p class="t5-bio">{{ $profile->bio ?: 'No description yet.' }}</p>
        @if($profile->primaryaddress || $user->address)
            <p class="t5-role"><i class="fas fa-map-marker-alt me-1"></i>{{ $profile->primaryaddress ? collect([$profile->primaryaddress->city?->name, $profile->primaryaddress->state?->name, $profile->primaryaddress->country?->name])->filter()->implode(', ') : $user->address }}</p>
        @endif
        <a href="{{ route('downloadVCard', $user->username) }}" class="t5-btn"><i class="fas fa-download"></i> Save contact</a>
        @include('frontend.pages.cards.partials._nfc')
        @if($profile->socials->count() > 0)
            <div class="t5-social">
                @foreach($profile->socials as $social)
                    @php $u = $social->shortlink?->shortlink ?? $social->shortlink?->link; @endphp
                    @if($u)<a href="{{ $u }}" target="_blank" rel="noopener noreferrer"><i class="{{ $social->platform->icon ?? 'fas fa-link' }}"></i></a>@endif
                @endforeach
            </div>
        @endif
        @if($profile->customlinks->count() > 0)
            @foreach($profile->customlinks as $link)
                @if($link->shortlink && ($lu = $link->shortlink->shortlink ?? $link->shortlink->link))
                    <a href="{{ $lu }}" target="_blank" rel="noopener noreferrer" class="t5-btn d-inline-flex mt-2">{{ $link->name ?? 'Link' }}</a>
                @endif
            @endforeach
        @endif
        @include('frontend.pages.cards.partials._shop')
        <div class="t5-footer">@include('frontend.pages.cards.partials._footer')</div>
    </div>
</body>
</html>
