<!DOCTYPE html>
<html lang="en">
<head>
    @include('frontend.pages.cards.partials._meta')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Source+Sans+3:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Source Sans 3',sans-serif; background:#faf9f7; color:#2c2c2c; min-height:100vh; padding:2rem 1rem; }
        .t6-wrap { max-width:720px; margin:0 auto; display:grid; grid-template-columns:1fr 1fr; gap:0; background:#fff; border-radius:16px; box-shadow:0 20px 60px rgba(0,0,0,.06); overflow:hidden; }
        @media(max-width:600px){ .t6-wrap { grid-template-columns:1fr; } }
        .t6-left { background:linear-gradient(180deg,#2d3748 0%,#1a202c 100%); padding:2rem; color:#fff; text-align:center; display:flex; flex-direction:column; align-items:center; justify-content:center; }
        .t6-avatar { width:120px; height:120px; border-radius:50%; object-fit:cover; border:4px solid rgba(255,255,255,.3); margin-bottom:1rem; }
        .t6-name { font-family:'Libre Baskerville',serif; font-size:1.5rem; font-weight:700; margin-bottom:.25rem; }
        .t6-role { font-size:.85rem; opacity:.9; margin-bottom:1rem; }
        .t6-social { display:flex; gap:.6rem; flex-wrap:wrap; justify-content:center; }
        .t6-social a { color:rgba(255,255,255,.8); width:36px; height:36px; border-radius:50%; border:1px solid rgba(255,255,255,.3); display:flex; align-items:center; justify-content:center; text-decoration:none; }
        .t6-social a:hover { background:rgba(255,255,255,.1); color:#fff; }
        .t6-right { padding:2rem; }
        .t6-bio { font-size:.95rem; line-height:1.7; color:#4a5568; margin-bottom:1.25rem; }
        .t6-meta { font-size:.875rem; color:#718096; margin-bottom:1rem; }
        .t6-meta a { color:#2b6cb0; text-decoration:none; }
        .t6-btn { display:inline-flex; align-items:center; gap:.4rem; padding:.6rem 1.25rem; background:#2d3748; color:#fff; border-radius:8px; text-decoration:none; font-weight:600; font-size:.9rem; }
        .t6-btn:hover { color:#fff; background:#1a202c; }
        .t6-links { margin-top:1rem; }
        .t6-link { display:block; padding:.5rem 0; color:#2b6cb0; text-decoration:none; font-size:.875rem; border-bottom:1px solid #e2e8f0; }
        .t6-link:hover { color:#1a365d; }
        .t6-footer { grid-column:1/-1; padding:1rem 2rem; font-size:.75rem; color:#a0aec0; text-align:center; border-top:1px solid #e2e8f0; }
        .t6-footer a { color:#2b6cb0; }
        * { box-sizing:border-box; }
        html { -webkit-text-size-adjust:100%; }
        body { overflow-x:hidden; }
        img { max-width:100%; height:auto; }
        .t6-wrap { width:100%; }
        .t6-btn { min-height:44px; min-width:44px; justify-content:center; }
        @media (max-width:480px) { .t6-left, .t6-right { padding:1.25rem; } .t6-avatar { width:100px; height:100px; } .t6-name { font-size:1.25rem; } }
    </style>
</head>
<body>
    <div class="t6-wrap">
        <div class="t6-left">
            <img src="{{ $avatarUrl ?? asset($profile->avatar ?? 'default/avatar/default.png') }}" alt="{{ $user->full_name }}" class="t6-avatar" loading="lazy">
            <h1 class="t6-name">{{ $user->full_name }}</h1>
            @if($profile->designation || $profile->organization)
                <p class="t6-role">{{ $profile->organization }}{{ $profile->organization && $profile->designation ? ' · ' : '' }}{{ $profile->designation }}</p>
            @endif
            @if($profile->socials->count() > 0)
                <div class="t6-social">
                    @foreach($profile->socials as $social)
                        @php $u = $social->shortlink?->shortlink ?? $social->shortlink?->link; @endphp
                        @if($u)<a href="{{ $u }}" target="_blank" rel="noopener noreferrer"><i class="{{ $social->platform->icon ?? 'fas fa-link' }}"></i></a>@endif
                    @endforeach
                </div>
            @endif
        </div>
        <div class="t6-right">
            <p class="t6-bio">{{ $profile->bio ?: 'No description yet.' }}</p>
            <div class="t6-meta">
                @if($profile->primaryaddress || $user->address)
                    <p><i class="fas fa-map-marker-alt me-1"></i>{{ $profile->primaryaddress ? collect([$profile->primaryaddress->city?->name, $profile->primaryaddress->state?->name, $profile->primaryaddress->country?->name])->filter()->implode(', ') : $user->address }}</p>
                @endif
                @if($user->email)<p><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></p>@endif
            </div>
            <a href="{{ route('downloadVCard', $user->username) }}" class="t6-btn"><i class="fas fa-download"></i> Save contact</a>
            @include('frontend.pages.cards.partials._nfc')
            @if($profile->customlinks->count() > 0)
                <div class="t6-links mt-3">
                    @foreach($profile->customlinks as $link)
                        @if($link->shortlink && ($lu = $link->shortlink->shortlink ?? $link->shortlink->link))
                            <a href="{{ $lu }}" target="_blank" rel="noopener noreferrer" class="t6-link">{{ $link->name ?? 'Link' }}</a>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
        @include('frontend.pages.cards.partials._shop')
        <div class="t6-footer">@include('frontend.pages.cards.partials._footer')</div>
    </div>
</body>
</html>
