@extends('user.main')
@section('title', 'Add Section - ' . config('app.name'))

@push('css')
<style>
.section-type-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
.section-type-card { border: 2px solid #e0e0e0; border-radius: 12px; padding: 1.5rem; text-align: center; cursor: pointer; transition: all .2s; }
.section-type-card:hover { border-color: #0d6efd; box-shadow: 0 4px 12px rgba(13,110,253,.15); transform: translateY(-2px); }
.section-type-card.selected { border-color: #0d6efd; background: #f0f7ff; }
.section-type-card i { font-size: 2.5rem; color: #0d6efd; margin-bottom: .75rem; }
.section-type-card h5 { margin: .5rem 0; font-size: 1rem; }
.section-type-card p { font-size: .85rem; color: #666; margin: 0; }
</style>
@endpush

@section('content')
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Add New Section</h4>
                                <p class="text-muted mb-0">Choose a section type to add to your landing page</p>
                            </div>
                        </div>
                        <div class="iq-card-body px-4">
                            <form action="{{ route('user.customizer.section.store', $customizationId) }}" method="POST" id="section-form">
                                @csrf
                                
                                <input type="hidden" name="section_type" id="section_type" required>
                                
                                <h5 class="mb-3">Select Section Type</h5>
                                <div class="section-type-grid">
                                    @foreach($sectionTypes as $key => $label)
                                        <div class="section-type-card" data-type="{{ $key }}">
                                            @if($key === 'banner')
                                                <i class="las la-image"></i>
                                            @elseif($key === 'team')
                                                <i class="las la-users"></i>
                                            @elseif($key === 'services')
                                                <i class="las la-concierge-bell"></i>
                                            @elseif($key === 'testimonials')
                                                <i class="las la-quote-left"></i>
                                            @elseif($key === 'gallery')
                                                <i class="las la-images"></i>
                                            @elseif($key === 'cta')
                                                <i class="las la-bullhorn"></i>
                                            @elseif($key === 'features')
                                                <i class="las la-star"></i>
                                            @elseif($key === 'about')
                                                <i class="las la-info-circle"></i>
                                            @elseif($key === 'contact')
                                                <i class="las la-envelope"></i>
                                            @else
                                                <i class="las la-square"></i>
                                            @endif
                                            <h5>{{ $label }}</h5>
                                            <p>
                                                @if($key === 'banner')
                                                    Hero banner with image and text
                                                @elseif($key === 'team')
                                                    Team member cards
                                                @elseif($key === 'services')
                                                    Services showcase
                                                @elseif($key === 'testimonials')
                                                    Customer testimonials
                                                @elseif($key === 'gallery')
                                                    Image gallery
                                                @elseif($key === 'cta')
                                                    Call-to-action button
                                                @elseif($key === 'features')
                                                    Features list
                                                @elseif($key === 'about')
                                                    About section
                                                @elseif($key === 'contact')
                                                    Contact form
                                                @endif
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                                
                                <div id="section-form-fields" style="display: none;">
                                    <hr class="my-4">
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="title" class="form-label">Section Title</label>
                                            <input type="text" class="form-control" id="title" name="title" placeholder="e.g. Our Team">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="sort_order" class="form-label">Display Order</label>
                                            <input type="number" class="form-control" id="sort_order" name="sort_order" value="0" min="0">
                                            <small class="form-text text-muted">Lower numbers appear first</small>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="subtitle" class="form-label">Subtitle/Description</label>
                                        <textarea class="form-control" id="subtitle" name="subtitle" rows="2" placeholder="Brief description of this section"></textarea>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="background_color" class="form-label">Background Color</label>
                                            <div style="display: flex; gap: .5rem; align-items: center;">
                                                <input type="color" id="bg_color_picker" value="#ffffff" style="width: 60px; height: 40px; border-radius: 6px;">
                                                <input type="text" class="form-control" id="background_color" name="background_color" value="#ffffff" placeholder="#ffffff">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="text_color" class="form-label">Text Color</label>
                                            <div style="display: flex; gap: .5rem; align-items: center;">
                                                <input type="color" id="text_color_picker" value="#212529" style="width: 60px; height: 40px; border-radius: 6px;">
                                                <input type="text" class="form-control" id="text_color" name="text_color" value="#212529" placeholder="#212529">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="background_image" class="form-label">Background Image (optional)</label>
                                        <input type="file" class="form-control" id="background_image" name="background_image" accept="image/*">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="content" class="form-label">Content</label>
                                        <textarea class="form-control" id="content" name="content" rows="6" placeholder="HTML content or text for this section"></textarea>
                                        <small class="form-text text-muted">You can use HTML tags for formatting</small>
                                    </div>
                                    
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                                        <label class="form-check-label" for="is_active">Active (show on public page)</label>
                                    </div>
                                    
                                    <div class="row text-right">
                                        <div class="col-12">
                                            <a href="{{ route('user.customizer.sections', $customizationId) }}" class="btn btn-secondary mr-2">Cancel</a>
                                            <button type="submit" class="btn btn-primary">Create Section</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var typeCards = document.querySelectorAll('.section-type-card');
    var formFields = document.getElementById('section-form-fields');
    var sectionTypeInput = document.getElementById('section_type');
    
    typeCards.forEach(function(card) {
        card.addEventListener('click', function() {
            typeCards.forEach(function(c) { c.classList.remove('selected'); });
            card.classList.add('selected');
            sectionTypeInput.value = card.dataset.type;
            formFields.style.display = 'block';
            formFields.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        });
    });
    
    // Color picker sync
    ['bg_color', 'text_color'].forEach(function(id) {
        var picker = document.getElementById(id + '_picker');
        var input = document.getElementById(id.replace('_', '_'));
        if (picker && input) {
            picker.addEventListener('input', function() {
                input.value = picker.value;
            });
            input.addEventListener('input', function() {
                if (/^#[0-9A-F]{6}$/i.test(input.value)) {
                    picker.value = input.value;
                }
            });
        }
    });
    
    var bgPicker = document.getElementById('bg_color_picker');
    var bgInput = document.getElementById('background_color');
    if (bgPicker && bgInput) {
        bgPicker.addEventListener('input', function() { bgInput.value = bgPicker.value; });
        bgInput.addEventListener('input', function() {
            if (/^#[0-9A-F]{6}$/i.test(bgInput.value)) { bgPicker.value = bgInput.value; }
        });
    }
    
    var textPicker = document.getElementById('text_color_picker');
    var textInput = document.getElementById('text_color');
    if (textPicker && textInput) {
        textPicker.addEventListener('input', function() { textInput.value = textPicker.value; });
        textInput.addEventListener('input', function() {
            if (/^#[0-9A-F]{6}$/i.test(textInput.value)) { textPicker.value = textInput.value; }
        });
    }
});
</script>
@endpush
