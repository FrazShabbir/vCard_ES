@extends('user.main')
@section('title', 'Appearance - ' . config('app.name'))

@push('css')
<style>
.appearance-live { background: #f8fff9; border: 1px solid #c3e6cb; border-radius: 10px; padding: 1rem 1.25rem; margin-bottom: 1.5rem; }
.appearance-tabs { display: flex; gap: .5rem; margin-bottom: 1.25rem; flex-wrap: wrap; }
.appearance-tabs a { padding: .5rem 1rem; border-radius: 8px; border: 1px solid #dee2e6; color: #333; text-decoration: none; font-weight: 600; }
.appearance-tabs a.active { background: #0d6efd; color: #fff; border-color: #0d6efd; }
.template-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 1.25rem; }
.template-option { border: 2px solid #e0e0e0; border-radius: 12px; overflow: hidden; background: #fff; cursor: pointer; }
.template-option.selected { border-color: #0d6efd; box-shadow: 0 0 0 2px rgba(13,110,253,.25); }
.template-preview-wrap { height: 280px; background: #f5f5f5; }
.template-preview-wrap iframe { width: 100%; height: 100%; border: none; pointer-events: none; }
.template-label { padding: .75rem 1rem; font-weight: 600; display: flex; align-items: center; gap: .5rem; }
.custom-card { border: 1px solid #e0e0e0; border-radius: 10px; padding: 1rem; margin-bottom: .75rem; }
.custom-card.active { border-color: #198754; background: #f8fff9; }
</style>
@endpush

@section('content')
<div id="content-page" class="content-page">
    <div class="container-fluid">
        <div class="iq-card">
            <div class="iq-card-header">
                <div class="iq-header-title">
                    <h4 class="card-title">Appearance</h4>
                    <p class="text-muted mb-0">Choose how your public vCard looks. Only one mode is live at a time.</p>
                </div>
            </div>
            <div class="iq-card-body">
                <div class="appearance-live">
                    <strong>Live now:</strong>
                    @if($appearanceMode === 'custom' && $activeCustomization)
                        Custom design — {{ $activeCustomization->name ?: 'Untitled' }}
                    @else
                        Template {{ $currentTemplateId }}
                    @endif
                    <a href="{{ $previewBaseUrl }}" target="_blank" class="btn btn-sm btn-outline-primary ml-2">View public card</a>
                </div>

                <div class="appearance-tabs">
                    <a href="{{ route('user.appearance', ['tab' => 'template']) }}" class="{{ $tab === 'template' ? 'active' : '' }}">Choose a template</a>
                    <a href="{{ route('user.appearance', ['tab' => 'custom']) }}" class="{{ $tab === 'custom' ? 'active' : '' }}">Build custom page</a>
                </div>

                @if($tab === 'template')
                    <form action="{{ route('user.appearance.mode') }}" method="POST" class="mb-3">
                        @csrf
                        <input type="hidden" name="appearance_mode" value="template">
                        @if($appearanceMode !== 'template')
                            <button type="submit" class="btn btn-success btn-sm mb-3">Make template mode live</button>
                        @endif
                    </form>
                    <form action="{{ route('user.profile.template.save') }}" method="POST" id="template-form">
                        @csrf
                        @method('PUT')
                        <div class="template-grid">
                            @foreach(range(1, 10) as $id)
                                @php $selected = ($currentTemplateId == $id); @endphp
                                <label class="template-option {{ $selected ? 'selected' : '' }}">
                                    <input type="radio" name="template_id" value="{{ $id }}" {{ $selected ? 'checked' : '' }} style="display:none;">
                                    <div class="template-preview-wrap">
                                        <iframe src="{{ $previewBaseUrl }}?preview_template={{ $id }}" title="Template {{ $id }}"></iframe>
                                    </div>
                                    <div class="template-label">Template {{ $id }}</div>
                                </label>
                            @endforeach
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Save &amp; use this template</button>
                        </div>
                    </form>
                @else
                    <form action="{{ route('user.appearance.mode') }}" method="POST" class="mb-3">
                        @csrf
                        <input type="hidden" name="appearance_mode" value="custom">
                        @if($appearanceMode !== 'custom')
                            <button type="submit" class="btn btn-success btn-sm mb-3">Make custom design live</button>
                        @endif
                    </form>
                    <p class="text-muted">Drag-and-drop blocks, colors, and fonts. Activate a design to put it live.</p>
                    <a href="{{ route('user.customizer.visual', $activeCustomization->id ?? null) }}" class="btn btn-primary mb-3">
                        <i class="las la-magic"></i> Open Page Builder
                    </a>
                    <a href="{{ route('user.customizer.sections') }}" class="btn btn-info mb-3">Manage Sections</a>
                    @forelse($customizations as $custom)
                        <div class="custom-card {{ $custom->is_active ? 'active' : '' }}">
                            <strong>{{ $custom->name ?: 'Untitled' }}</strong>
                            @if($custom->is_active)<span class="badge badge-success ml-2">Active</span>@endif
                            <div class="mt-2">
                                <a href="{{ route('user.customizer.visual', $custom->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                @if(!$custom->is_active)
                                    <form action="{{ route('user.customizer.activate', $custom->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">Activate</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">No custom designs yet. Open the Page Builder to create one.</p>
                    @endforelse
                @endif
            </div>
        </div>
    </div>
</div>
<script>
document.querySelectorAll('.template-option').forEach(function(el) {
    el.addEventListener('click', function() {
        document.querySelectorAll('.template-option').forEach(function(o) { o.classList.remove('selected'); });
        el.classList.add('selected');
        el.querySelector('input[type=radio]').checked = true;
    });
});
</script>
@endsection
