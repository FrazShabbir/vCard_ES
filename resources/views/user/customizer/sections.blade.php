@extends('user.main')
@section('title', 'Manage Sections - ' . config('app.name'))

@push('css')
<link href="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.css" rel="stylesheet">
<style>
.sections-list { display: flex; flex-direction: column; gap: 1rem; }
.section-item { background: #fff; border: 2px solid #e0e0e0; border-radius: 12px; padding: 1.25rem; transition: all .2s; cursor: move; position: relative; }
.section-item:hover { border-color: #0d6efd; box-shadow: 0 4px 12px rgba(13,110,253,.15); }
.section-item.sortable-ghost { opacity: 0.4; background: #f0f0f0; }
.section-item.sortable-drag { opacity: 0.8; transform: rotate(2deg); }
.section-item-header { display: flex; justify-content: space-between; align-items: start; margin-bottom: .75rem; }
.section-item-title { font-size: 1.1rem; font-weight: 600; margin: 0; }
.section-item-type { display: inline-block; padding: .25rem .5rem; background: #6c757d; color: #fff; border-radius: 4px; font-size: .75rem; }
.section-item-content { color: #666; font-size: .9rem; margin-bottom: .75rem; }
.section-item-actions { display: flex; gap: .5rem; flex-wrap: wrap; }
.section-badge { display: inline-block; padding: .2rem .4rem; border-radius: 4px; font-size: .7rem; font-weight: 600; }
.section-badge.active { background: #198754; color: #fff; }
.section-badge.inactive { background: #dc3545; color: #fff; }
.section-drag-handle { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #999; font-size: 1.5rem; cursor: grab; }
.section-drag-handle:active { cursor: grabbing; }
.section-item-content-wrapper { padding-left: 2.5rem; }
.order-saved-message { position: fixed; top: 20px; right: 20px; background: #198754; color: #fff; padding: 1rem 1.5rem; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 9999; display: none; }
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
                                <h4 class="card-title">Manage Sections</h4>
                                <p class="text-muted mb-0">
                                    Add custom sections like banners, team cards, services, and more to create a full landing page experience.
                                    @if($customization)
                                        <span class="badge badge-info ml-2">For: {{ $customization->name }}</span>
                                    @else
                                        <span class="badge badge-secondary ml-2">Global Sections</span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <a href="{{ route('user.customizer') }}" class="btn btn-secondary mr-2">
                                    <i class="las la-arrow-left"></i> Back to Customizer
                                </a>
                                <a href="{{ route('user.customizer.section.create', $customizationId) }}" class="btn btn-primary">
                                    <i class="las la-plus"></i> Add Section
                                </a>
                            </div>
                        </div>
                        <div class="iq-card-body px-4">
                            @if($sections->isEmpty())
                                <div class="text-center py-5">
                                    <i class="las la-layer-group" style="font-size: 4rem; color: #ccc; margin-bottom: 1rem;"></i>
                                    <h5>No sections yet</h5>
                                    <p class="text-muted">Add sections to build a complete landing page for your vCard.</p>
                                    <a href="{{ route('user.customizer.section.create', $customizationId) }}" class="btn btn-primary mt-3">
                                        <i class="las la-plus"></i> Add First Section
                                    </a>
                                </div>
                            @else
                                <div class="mb-3 p-3 bg-info text-white rounded">
                                    <i class="las la-info-circle"></i> <strong>Drag and drop</strong> sections to reorder them. Changes are saved automatically.
                                </div>
                                <div class="sections-list" id="sections-list">
                                    @foreach($sections as $section)
                                        <div class="section-item" data-section-id="{{ $section->id }}">
                                            <div class="section-drag-handle">
                                                <i class="las la-grip-vertical"></i>
                                            </div>
                                            <div class="section-item-content-wrapper">
                                                <div class="section-item-header">
                                                    <div>
                                                        <h5 class="section-item-title">{{ $section->title ?: 'Untitled Section' }}</h5>
                                                        <span class="section-item-type">{{ ucfirst($section->section_type) }}</span>
                                                        <span class="section-badge {{ $section->is_active ? 'active' : 'inactive' }}">
                                                            {{ $section->is_active ? 'Active' : 'Inactive' }}
                                                        </span>
                                                    </div>
                                                    <div class="section-item-actions">
                                                        <a href="{{ route('user.customizer.section.edit', $section->id) }}" class="btn btn-primary btn-sm">
                                                            <i class="las la-edit"></i> Edit
                                                        </a>
                                                        <form action="{{ route('user.customizer.section.delete', $section->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete this section?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm">
                                                                <i class="las la-trash"></i> Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                                @if($section->subtitle)
                                                    <div class="section-item-content">{{ Str::limit($section->subtitle, 150) }}</div>
                                                @endif
                                                <div class="text-muted small">
                                                    Order: <span class="section-order-display">{{ $section->sort_order }}</span> | 
                                                    Created: {{ $section->created_at->format('M d, Y') }}
                                                </div>
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
    
    <div class="order-saved-message" id="order-saved-message">
        <i class="las la-check-circle"></i> Section order saved!
    </div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var sectionsList = document.getElementById('sections-list');
    if (!sectionsList) return;
    
    var sortable = new Sortable(sectionsList, {
        handle: '.section-drag-handle',
        animation: 150,
        ghostClass: 'sortable-ghost',
        dragClass: 'sortable-drag',
        onEnd: function(evt) {
            // Update order numbers in display
            updateOrderDisplay();
            
            // Save new order to server
            saveSectionOrder();
        }
    });
    
    function updateOrderDisplay() {
        var items = sectionsList.querySelectorAll('.section-item');
        items.forEach(function(item, index) {
            var orderDisplay = item.querySelector('.section-order-display');
            if (orderDisplay) {
                orderDisplay.textContent = index;
            }
        });
    }
    
    function saveSectionOrder() {
        var items = sectionsList.querySelectorAll('.section-item');
        var sections = [];
        
        items.forEach(function(item, index) {
            sections.push({
                id: item.dataset.sectionId,
                order: index
            });
        });
        
        fetch('{{ route("user.customizer.sections.order") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ sections: sections })
        })
        .then(function(response) {
            return response.json();
        })
        .then(function(data) {
            if (data.success) {
                showSavedMessage();
            } else {
                console.error('Failed to save order:', data.message);
            }
        })
        .catch(function(error) {
            console.error('Error saving order:', error);
        });
    }
    
    function showSavedMessage() {
        var message = document.getElementById('order-saved-message');
        if (message) {
            message.style.display = 'block';
            setTimeout(function() {
                message.style.display = 'none';
            }, 2000);
        }
    }
});
</script>
@endpush
