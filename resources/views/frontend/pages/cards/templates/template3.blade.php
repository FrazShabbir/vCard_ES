<!DOCTYPE html>
<html lang="en">
<head>
    @include('frontend.pages.cards.partials._meta')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,600;9..40,700&display=swap" rel="stylesheet">
    <style>
        :root { --t3-bg:#f8fafc; --t3-card:#fff; --t3-text:#1e293b; --t3-muted:#64748b; --t3-accent:#0ea5e9; }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'DM Sans',sans-serif; background:var(--t3-bg); color:var(--t3-text); min-height:100vh; padding:2rem 1rem; }
        .t3-card { max-width:420px; margin:0 auto; background:var(--t3-card); border-radius:24px; box-shadow:0 25px 50px -12px rgba(0,0,0,.08); overflow:hidden; }
        .t3-cover { height:140px; background:linear-gradient(135deg,#0ea5e9,#06b6d4); }
        .t3-avatar { width:100px; height:100px; border-radius:50%; border:4px solid #fff; margin:-50px auto 0; display:block; object-fit:cover; box-shadow:0 10px 25px -5px rgba(0,0,0,.15); }
        .t3-body { padding:1.25rem 1.5rem 1.5rem; text-align:center; }
        .t3-name { font-size:1.5rem; font-weight:700; margin-bottom:.25rem; }
        .t3-role { color:var(--t3-muted); font-size:.9rem; margin-bottom:1rem; }
        .t3-bio { font-size:.875rem; line-height:1.6; color:var(--t3-muted); margin-bottom:1.25rem; }
        .t3-loc { font-size:.8rem; color:var(--t3-muted); margin-bottom:1rem; }
        .t3-btn { display:inline-flex; align-items:center; gap:.5rem; padding:.6rem 1.5rem; background:var(--t3-accent); color:#fff; border-radius:999px; text-decoration:none; font-weight:600; font-size:.9rem; border:none; }
        .t3-btn:hover { color:#fff; opacity:.95; }
        .t3-social { display:flex; justify-content:center; gap:1rem; margin-top:1.25rem; flex-wrap:wrap; }
        .t3-social a { width:40px; height:40px; border-radius:50%; background:var(--t3-bg); color:var(--t3-text); display:flex; align-items:center; justify-content:center; text-decoration:none; transition:transform .2s; }
        .t3-social a:hover { transform:scale(1.1); color:var(--t3-accent); }
        .t3-links { margin-top:1rem; }
        .t3-link { display:block; padding:.6rem 1rem; background:var(--t3-bg); border-radius:12px; margin-bottom:.5rem; color:var(--t3-text); text-decoration:none; font-size:.875rem; text-align:center; }
        .t3-link:hover { background:#e2e8f0; }
        .t3-footer { text-align:center; padding:1.5rem; font-size:.75rem; color:var(--t3-muted); }
        .t3-footer a { color:var(--t3-accent); text-decoration:none; }
        * { box-sizing:border-box; }
        html { -webkit-text-size-adjust:100%; }
        body { overflow-x:hidden; }
        img { max-width:100%; height:auto; }
        .t3-card { width:100%; }
        .t3-btn { min-height:44px; min-width:44px; justify-content:center; }
        @media (max-width:480px) { body { padding:1rem .75rem; } .t3-avatar { width:80px; height:80px; margin-top:-40px; } .t3-name { font-size:1.25rem; } }
    </style>
</head>
<body>
    <div class="t3-card">
        <div class="t3-cover"></div>
        <img src="{{ $avatarUrl ?? asset($profile->avatar ?? 'default/avatar/default.png') }}" alt="{{ $user->full_name }}" class="t3-avatar" loading="lazy">
        <div class="t3-body">
            <h1 class="t3-name">{{ $user->full_name }}</h1>
            @if($profile->designation || $profile->organization)
                <p class="t3-role">{{ $profile->organization }}{{ $profile->organization && $profile->designation ? ' · ' : '' }}{{ $profile->designation }}</p>
            @endif
            <p class="t3-bio">{{ $profile->bio ?: 'No description yet.' }}</p>
            @if(($profile->primaryaddress && ($profile->primaryaddress->city || $profile->primaryaddress->state || $profile->primaryaddress->country)) || $user->address)
                <p class="t3-loc"><i class="fas fa-map-marker-alt me-1"></i>{{ $profile->primaryaddress ? collect([$profile->primaryaddress->city?->name, $profile->primaryaddress->state?->name, $profile->primaryaddress->country?->name])->filter()->implode(', ') : $user->address }}</p>
            @endif
            <a href="{{ route('downloadVCard', $user->username) }}" class="t3-btn"><i class="fas fa-download"></i> Save contact</a>
            @include('frontend.pages.cards.partials._nfc')
            @if($profile->socials->count() > 0)
                <div class="t3-social">
                    @foreach($profile->socials as $social)
                        @php $u = $social->shortlink?->shortlink ?? $social->shortlink?->link; @endphp
                        @if($u)<a href="{{ $u }}" target="_blank" rel="noopener noreferrer"><i class="{{ $social->platform->icon ?? 'fas fa-link' }}"></i></a>@endif
                    @endforeach
                </div>
            @endif
            @if($profile->customlinks->count() > 0)
                <div class="t3-links">
                    @foreach($profile->customlinks as $link)
                        @if($link->shortlink && ($lu = $link->shortlink->shortlink ?? $link->shortlink->link))
                            <a href="{{ $lu }}" target="_blank" rel="noopener noreferrer" class="t3-link">{{ $link->name ?? 'Link' }}</a>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
        @include('frontend.pages.cards.partials._shop')
        <div class="t3-footer">@include('frontend.pages.cards.partials._footer')</div>
    </div>
</body>
</html>
