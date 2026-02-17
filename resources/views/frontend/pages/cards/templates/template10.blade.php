<!DOCTYPE html>
<html lang="en">
<head>
    @include('frontend.pages.cards.partials._meta')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Space Grotesk',sans-serif; background:#0a0a0a; color:#e0e0e0; min-height:100vh; padding:2rem 1rem; }
        .t10-card { max-width:400px; margin:0 auto; border:1px solid #00ff88; border-radius:4px; padding:2rem; position:relative; box-shadow:0 0 30px rgba(0,255,136,.08); }
        .t10-card::before { content:''; position:absolute; inset:-1px; border-radius:4px; padding:1px; background:linear-gradient(135deg,#00ff88,#00ccff); -webkit-mask:linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0); mask:linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0); -webkit-mask-composite:xor; mask-composite:exclude; pointer-events:none; opacity:.6; }
        .t10-avatar { width:90px; height:90px; border-radius:4px; object-fit:cover; border:1px solid #00ff88; margin-bottom:1.25rem; }
        .t10-name { font-size:1.5rem; font-weight:700; color:#fff; margin-bottom:.3rem; }
        .t10-role { font-family:'IBM Plex Mono',monospace; font-size:.8rem; color:#00ff88; margin-bottom:1rem; letter-spacing:.05em; }
        .t10-bio { font-size:.875rem; line-height:1.7; color:#a0a0a0; margin-bottom:1.25rem; }
        .t10-meta { font-size:.8rem; color:#666; margin-bottom:1rem; }
        .t10-meta a { color:#00ff88; text-decoration:none; }
        .t10-btn { display:inline-flex; align-items:center; gap:.4rem; padding:.55rem 1.25rem; background:transparent; color:#00ff88; border:1px solid #00ff88; border-radius:2px; text-decoration:none; font-size:.85rem; font-family:'IBM Plex Mono',monospace; }
        .t10-btn:hover { background:#00ff88; color:#0a0a0a; }
        .t10-social { margin-top:1.25rem; display:flex; gap:.75rem; flex-wrap:wrap; }
        .t10-social a { color:#666; font-size:1.1rem; }
        .t10-social a:hover { color:#00ff88; }
        .t10-footer { margin-top:1.5rem; font-size:.7rem; color:#444; font-family:'IBM Plex Mono',monospace; }
        .t10-footer a { color:#00ff88; }
        * { box-sizing:border-box; }
        html { -webkit-text-size-adjust:100%; }
        body { overflow-x:hidden; padding:1rem; }
        img { max-width:100%; height:auto; }
        .t10-card { width:100%; }
        .t10-btn { min-height:44px; min-width:44px; justify-content:center; }
        @media (max-width:480px) { .t10-avatar { width:72px; height:72px; } .t10-name { font-size:1.3rem; } }
    </style>
</head>
<body>
    <div class="t10-card">
        <img src="{{ $avatarUrl ?? asset($profile->avatar ?? 'default/avatar/default.png') }}" alt="{{ $user->full_name }}" class="t10-avatar" loading="lazy">
        <h1 class="t10-name">{{ $user->full_name }}</h1>
        @if($profile->designation || $profile->organization)
            <p class="t10-role">{{ strtoupper($profile->organization ?? '') }}{{ $profile->organization && $profile->designation ? ' / ' : '' }}{{ strtoupper($profile->designation ?? '') }}</p>
        @endif
        <p class="t10-bio">{{ $profile->bio ?: 'No description yet.' }}</p>
        <div class="t10-meta">
            @if($profile->primaryaddress || $user->address)
                <p>> {{ $profile->primaryaddress ? collect([$profile->primaryaddress->city?->name, $profile->primaryaddress->state?->name, $profile->primaryaddress->country?->name])->filter()->implode(', ') : $user->address }}</p>
            @endif
            @if($user->email)<p><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></p>@endif
        </div>
        <a href="{{ route('downloadVCard', $user->username) }}" class="t10-btn"><i class="fas fa-download"></i> Save contact</a>
        @include('frontend.pages.cards.partials._nfc')
        @if($profile->socials->count() > 0)
            <div class="t10-social">
                @foreach($profile->socials as $social)
                    @php $u = $social->shortlink?->shortlink ?? $social->shortlink?->link; @endphp
                    @if($u)<a href="{{ $u }}" target="_blank" rel="noopener noreferrer"><i class="{{ $social->platform->icon ?? 'fas fa-link' }}"></i></a>@endif
                @endforeach
            </div>
        @endif
        @if($profile->customlinks->count() > 0)
            @foreach($profile->customlinks as $link)
                @if($link->shortlink && ($lu = $link->shortlink->shortlink ?? $link->shortlink->link))
                    <a href="{{ $lu }}" target="_blank" rel="noopener noreferrer" class="t10-btn d-inline-flex mt-2">{{ $link->name ?? 'LINK' }}</a>
                @endif
            @endforeach
        @endif
        @include('frontend.pages.cards.partials._shop')
        <div class="t10-footer">@include('frontend.pages.cards.partials._footer')</div>
    </div>
</body>
</html>
