<!DOCTYPE html>
<html lang="en">
<head>
    @include('frontend.pages.cards.partials._meta')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --t4-bg:#0f172a; --t4-card:#1e293b; --t4-text:#f1f5f9; --t4-muted:#94a3b8; --t4-accent:#a78bfa; }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Space Grotesk',sans-serif; background:var(--t4-bg); color:var(--t4-text); min-height:100vh; padding:2rem 1rem; }
        .t4-card { max-width:400px; margin:0 auto; background:var(--t4-card); border-radius:20px; border:1px solid rgba(255,255,255,.06); padding:2rem; text-align:center; }
        .t4-avatar { width:120px; height:120px; border-radius:50%; object-fit:cover; margin-bottom:1.25rem; border:3px solid var(--t4-accent); }
        .t4-name { font-size:1.6rem; font-weight:700; margin-bottom:.35rem; }
        .t4-role { color:var(--t4-muted); font-size:.9rem; margin-bottom:1rem; }
        .t4-bio { font-size:.875rem; line-height:1.65; color:var(--t4-muted); margin-bottom:1.25rem; }
        .t4-meta { font-size:.8rem; color:var(--t4-muted); margin-bottom:1rem; }
        .t4-meta a { color:var(--t4-accent); text-decoration:none; }
        .t4-btn { display:inline-block; padding:.65rem 1.5rem; background:var(--t4-accent); color:#fff; border-radius:10px; text-decoration:none; font-weight:600; font-size:.9rem; }
        .t4-btn:hover { color:#fff; opacity:.9; }
        .t4-social { display:flex; justify-content:center; gap:.75rem; margin-top:1.5rem; flex-wrap:wrap; }
        .t4-social a { color:var(--t4-muted); font-size:1.25rem; }
        .t4-social a:hover { color:var(--t4-accent); }
        .t4-footer { margin-top:1.5rem; font-size:.7rem; color:var(--t4-muted); }
        .t4-footer a { color:var(--t4-accent); }
        * { box-sizing:border-box; }
        html { -webkit-text-size-adjust:100%; }
        body { overflow-x:hidden; }
        img { max-width:100%; height:auto; }
        .t4-card { width:100%; }
        .t4-btn { min-height:44px; min-width:44px; padding:.75rem 1.5rem; }
        @media (max-width:480px) { body { padding:1rem .75rem; } .t4-avatar { width:100px; height:100px; } .t4-name { font-size:1.35rem; } }
    </style>
</head>
<body>
    <div class="t4-card">
        <img src="{{ $avatarUrl ?? asset($profile->avatar ?? 'default/avatar/default.png') }}" alt="{{ $user->full_name }}" class="t4-avatar" loading="lazy">
        <h1 class="t4-name">{{ $user->full_name }}</h1>
        @if($profile->designation || $profile->organization)
            <p class="t4-role">{{ $profile->organization }}{{ $profile->organization && $profile->designation ? ' · ' : '' }}{{ $profile->designation }}</p>
        @endif
        <p class="t4-bio">{{ $profile->bio ?: 'No description yet.' }}</p>
        <div class="t4-meta">
            @if($profile->primaryaddress || $user->address)
                <p>{{ $profile->primaryaddress ? collect([$profile->primaryaddress->city?->name, $profile->primaryaddress->state?->name, $profile->primaryaddress->country?->name])->filter()->implode(', ') : $user->address }}</p>
            @endif
            @if($user->email)<p><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></p>@endif
        </div>
        <a href="{{ route('downloadVCard', $user->username) }}" class="t4-btn"><i class="fas fa-download me-1"></i>Save contact</a>
        @include('frontend.pages.cards.partials._nfc')
        @if($profile->socials->count() > 0)
            <div class="t4-social">
                @foreach($profile->socials as $social)
                    @php $u = $social->shortlink?->shortlink ?? $social->shortlink?->link; @endphp
                    @if($u)<a href="{{ $u }}" target="_blank" rel="noopener noreferrer"><i class="{{ $social->platform->icon ?? 'fas fa-link' }}"></i></a>@endif
                @endforeach
            </div>
        @endif
        @if($profile->customlinks->count() > 0)
            @foreach($profile->customlinks as $link)
                @if($link->shortlink && ($lu = $link->shortlink->shortlink ?? $link->shortlink->link))
                    <a href="{{ $lu }}" target="_blank" rel="noopener noreferrer" class="t4-btn d-inline-block mt-2">{{ $link->name ?? 'Link' }}</a>
                @endif
            @endforeach
        @endif
        @include('frontend.pages.cards.partials._shop')
        <div class="t4-footer">@include('frontend.pages.cards.partials._footer')</div>
    </div>
</body>
</html>
