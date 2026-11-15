@extends('user.main')
@section('title', 'Template Customizer - ' . config('app.name'))

@push('css')
<style>
.customizer-list { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem; }
.customizer-card { background: #fff; border: 2px solid #e0e0e0; border-radius: 12px; padding: 1.25rem; transition: all .2s; }
.customizer-card:hover { border-color: #0d6efd; box-shadow: 0 4px 12px rgba(13,110,253,.15); }
.customizer-card.active { border-color: #198754; background: #f8fff9; }
.customizer-card-header { display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem; }
.customizer-card-name { font-size: 1.1rem; font-weight: 600; margin: 0; }
.customizer-card-badge { display: inline-block; padding: .25rem .5rem; background: #198754; color: #fff; border-radius: 4px; font-size: .75rem; font-weight: 600; }
.customizer-card-preview { width: 100%; height: 120px; background: linear-gradient(135deg, var(--preview-primary, #0d6efd), var(--preview-accent, #198754)); border-radius: 8px; margin-bottom: .75rem; position: relative; }
.customizer-card-meta { font-size: .85rem; color: #666; margin-bottom: .5rem; }
.customizer-card-actions { display: flex; gap: .5rem; flex-wrap: wrap; }
.customizer-card-actions .btn { flex: 1; min-width: 80px; }
</style>
@endpush

@section('content')
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between align-items-center flex-wrap">
                            <div class="iq-header-title">
                                <h4 class="card-title">Template Customizer</h4>
                                <p class="text-muted mb-0">
                                    Create and manage custom designs for your vCard. You can have multiple customizations and switch between them.
                                    @if($isBusiness)
                                        <span class="badge badge-success ml-2">Business Account</span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <a href="{{ route('user.customizer.visual', $activeCustomization->id ?? null) }}" class="btn btn-success mr-2">
                                    <i class="las la-magic"></i> Page Builder
                                </a>
                                <a href="{{ route('user.customizer.sections') }}" class="btn btn-info mr-2">
                                    <i class="las la-layer-group"></i> Manage Sections
                                </a>
                                <a href="{{ route('user.customizer.create') }}" class="btn btn-primary">
                                    <i class="las la-plus"></i> Create New Design
                                </a>
                            </div>
                        </div>
                        <div class="iq-card-body px-4">
                            @if($customizations->isEmpty())
                                <div class="text-center py-5">
                                    <i class="las la-palette" style="font-size: 4rem; color: #ccc; margin-bottom: 1rem;"></i>
                                    <h5>No customizations yet</h5>
                                    <p class="text-muted">Create your first custom design to personalize your vCard.</p>
                                    <a href="{{ route('user.customizer.create') }}" class="btn btn-primary mt-3">
                                        <i class="las la-plus"></i> Create First Design
                                    </a>
                                </div>
                            @else
                                <div class="customizer-list">
                                    @foreach($customizations as $custom)
                                        <div class="customizer-card {{ $custom->is_active ? 'active' : '' }}" style="--preview-primary: {{ $custom->primary_color ?? '#0d6efd' }}; --preview-accent: {{ $custom->accent_color ?? '#198754' }};">
                                            <div class="customizer-card-header">
                                                <h5 class="customizer-card-name">{{ $custom->name ?: 'Unnamed Design' }}</h5>
                                                @if($custom->is_active)
                                                    <span class="customizer-card-badge">Active</span>
                                                @endif
                                            </div>
                                            <div class="customizer-card-preview"></div>
                                            <div class="customizer-card-meta">
                                                <strong>Template:</strong> {{ $custom->template_id }}<br>
                                                <strong>Created:</strong> {{ $custom->created_at->format('M d, Y') }}
                                            </div>
                                            <div class="customizer-card-actions">
                                                <a href="{{ route('user.customizer.visual', $custom->id) }}" class="btn btn-primary btn-sm" style="flex: 1;" title="Visual Editor">
                                                    <i class="las la-magic"></i> Visual Editor
                                                </a>
                                                @if(!$custom->is_active)
                                                    <form action="{{ route('user.customizer.activate', $custom->id) }}" method="POST" style="flex: 1;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm w-100">Activate</button>
                                                    </form>
                                                @endif
                                                <a href="{{ route('user.customizer.edit', $custom->id) }}" class="btn btn-secondary btn-sm" style="flex: 1;">Edit</a>
                                                <a href="{{ route('user.customizer.sections', $custom->id) }}" class="btn btn-info btn-sm" style="flex: 1;" title="Manage Sections">
                                                    <i class="las la-layer-group"></i> Sections
                                                </a>
                                                @if(!$custom->is_active)
                                                    <form action="{{ route('user.customizer.delete', $custom->id) }}" method="POST" style="flex: 1;" onsubmit="return confirm('Delete this customization?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm w-100">Delete</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
