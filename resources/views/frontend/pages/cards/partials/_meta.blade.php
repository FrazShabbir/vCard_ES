<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<meta name="theme-color" content="#ffffff">
<title>{{ $user->full_name }}@if($profile->designation ?? $profile->organization) – {{ $profile->designation ?? $profile->organization }}@endif</title>
<meta name="description" content="{{ Str::limit(strip_tags($profile->bio ?? $user->full_name . ' – ' . config('app.name') . ' contact card'), 160) }}">
<meta property="og:title" content="{{ $user->full_name }}">
<meta property="og:description" content="{{ Str::limit(strip_tags($profile->bio ?? ''), 200) }}">
<meta property="og:image" content="{{ $avatarUrl ?? asset($profile->avatar ?? 'default/avatar/default.png') }}">
<meta property="og:url" content="{{ $profileUrl ?? url()->current() }}">
<meta property="og:type" content="profile">
