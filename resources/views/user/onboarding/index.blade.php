@extends('user.main')
@section('title', 'Get started - ' . config('app.name'))

@section('content')
<div id="content-page" class="content-page">
    <div class="container-fluid">
        <div class="iq-card">
            <div class="iq-card-header d-flex justify-content-between align-items-center">
                <div class="iq-header-title">
                    <h4 class="card-title">Welcome{{ $user->first_name ? ', ' . $user->first_name : '' }}</h4>
                    <p class="text-muted mb-0">Follow these steps to launch your digital card.</p>
                </div>
                @if(!$allDone)
                    <form action="{{ route('user.onboarding.skip') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-link text-muted">Skip for now</button>
                    </form>
                @endif
            </div>
            <div class="iq-card-body">
                @foreach($steps as $i => $step)
                    <div class="d-flex align-items-start mb-3 p-3 border rounded {{ $step['done'] ? 'border-success bg-light' : '' }}">
                        <div class="mr-3" style="font-size:1.5rem; min-width:2rem;">
                            @if($step['done'])
                                <i class="las la-check-circle text-success"></i>
                            @else
                                <span class="badge badge-primary">{{ $i + 1 }}</span>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-1">{{ $step['title'] }}</h5>
                            <p class="text-muted mb-2">{{ $step['description'] }}</p>
                            @if(!$step['done'])
                                <a href="{{ route($step['route']) }}" class="btn btn-sm btn-primary">
                                    {{ $next && $next['key'] === $step['key'] ? 'Continue' : 'Open' }}
                                </a>
                            @else
                                <span class="text-success small">Done</span>
                                <a href="{{ route($step['route']) }}" class="btn btn-sm btn-outline-secondary ml-2">Edit</a>
                            @endif
                        </div>
                    </div>
                @endforeach

                @if($allDone)
                    <form action="{{ route('user.onboarding.complete') }}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" class="btn btn-success">Go to dashboard</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
