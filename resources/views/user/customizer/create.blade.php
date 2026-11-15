@extends('user.main')
@section('title', 'Create Custom Design - ' . config('app.name'))

@push('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-colorpicker@3.4.0/dist/css/bootstrap-colorpicker.min.css" rel="stylesheet">
<style>
.customizer-form { max-width: 900px; margin: 0 auto; }
.color-picker-group { display: flex; align-items: center; gap: .75rem; }
.color-picker-group input[type="color"] { width: 60px; height: 40px; border: 1px solid #ddd; border-radius: 6px; cursor: pointer; }
.color-picker-group input[type="text"] { flex: 1; }
.preview-box { background: #f8f9fa; border: 2px dashed #dee2e6; border-radius: 12px; padding: 2rem; text-align: center; min-height: 200px; display: flex; align-items: center; justify-content: center; margin-top: 1rem; }
.preview-box-content { max-width: 300px; }
.preview-box h3 { margin-bottom: .5rem; }
.preview-box p { color: #666; margin: 0; }
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
                                <h4 class="card-title">Create Custom Design</h4>
                            </div>
                        </div>
                        <div class="iq-card-body px-4 customizer-form">
                            <form action="{{ route('user.customizer.store') }}" method="POST" id="customizer-form">
                                @csrf
                                
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label">Design Name <small class="text-muted">(optional)</small></label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="e.g. My Business Card" value="{{ old('name') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="template_id" class="form-label">Base Template <span class="text-danger">*</span></label>
                                        <select class="form-control" id="template_id" name="template_id" required>
                                            @foreach(range(1, 10) as $id)
                                                <option value="{{ $id }}" {{ ($defaults['template_id'] ?? 1) == $id ? 'selected' : '' }}>Template {{ $id }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <h5 class="mt-4 mb-3">Colors</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Primary Color</label>
                                        <div class="color-picker-group">
                                            <input type="color" id="primary_color_picker" value="{{ $defaults['primary_color'] ?? '#0d6efd' }}">
                                            <input type="text" class="form-control" id="primary_color" name="primary_color" value="{{ old('primary_color', $defaults['primary_color'] ?? '#0d6efd') }}" placeholder="#0d6efd">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Accent Color</label>
                                        <div class="color-picker-group">
                                            <input type="color" id="accent_color_picker" value="{{ $defaults['accent_color'] ?? '#198754' }}">
                                            <input type="text" class="form-control" id="accent_color" name="accent_color" value="{{ old('accent_color', $defaults['accent_color'] ?? '#198754') }}" placeholder="#198754">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Background Color</label>
                                        <div class="color-picker-group">
                                            <input type="color" id="background_color_picker" value="{{ $defaults['background_color'] ?? '#ffffff' }}">
                                            <input type="text" class="form-control" id="background_color" name="background_color" value="{{ old('background_color', $defaults['background_color'] ?? '#ffffff') }}" placeholder="#ffffff">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Text Color</label>
                                        <div class="color-picker-group">
                                            <input type="color" id="text_color_picker" value="{{ $defaults['text_color'] ?? '#212529' }}">
                                            <input type="text" class="form-control" id="text_color" name="text_color" value="{{ old('text_color', $defaults['text_color'] ?? '#212529') }}" placeholder="#212529">
                                        </div>
                                    </div>
                                </div>

                                <h5 class="mt-4 mb-3">Typography</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="heading_font" class="form-label">Heading Font</label>
                                        <select class="form-control" id="heading_font" name="heading_font">
                                            <option value="Poppins" {{ ($defaults['heading_font'] ?? 'Poppins') == 'Poppins' ? 'selected' : '' }}>Poppins</option>
                                            <option value="Roboto" {{ ($defaults['heading_font'] ?? '') == 'Roboto' ? 'selected' : '' }}>Roboto</option>
                                            <option value="Open Sans" {{ ($defaults['heading_font'] ?? '') == 'Open Sans' ? 'selected' : '' }}>Open Sans</option>
                                            <option value="Lato" {{ ($defaults['heading_font'] ?? '') == 'Lato' ? 'selected' : '' }}>Lato</option>
                                            <option value="Montserrat" {{ ($defaults['heading_font'] ?? '') == 'Montserrat' ? 'selected' : '' }}>Montserrat</option>
                                            <option value="Inter" {{ ($defaults['heading_font'] ?? '') == 'Inter' ? 'selected' : '' }}>Inter</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="body_font" class="form-label">Body Font</label>
                                        <select class="form-control" id="body_font" name="body_font">
                                            <option value="Poppins" {{ ($defaults['body_font'] ?? 'Poppins') == 'Poppins' ? 'selected' : '' }}>Poppins</option>
                                            <option value="Roboto" {{ ($defaults['body_font'] ?? '') == 'Roboto' ? 'selected' : '' }}>Roboto</option>
                                            <option value="Open Sans" {{ ($defaults['body_font'] ?? '') == 'Open Sans' ? 'selected' : '' }}>Open Sans</option>
                                            <option value="Lato" {{ ($defaults['body_font'] ?? '') == 'Lato' ? 'selected' : '' }}>Lato</option>
                                            <option value="Montserrat" {{ ($defaults['body_font'] ?? '') == 'Montserrat' ? 'selected' : '' }}>Montserrat</option>
                                            <option value="Inter" {{ ($defaults['body_font'] ?? '') == 'Inter' ? 'selected' : '' }}>Inter</option>
                                        </select>
                                    </div>
                                </div>

                                <h5 class="mt-4 mb-3">Layout & Style</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="layout_style" class="form-label">Layout Style</label>
                                        <select class="form-control" id="layout_style" name="layout_style">
                                            <option value="centered" {{ ($defaults['layout_style'] ?? 'centered') == 'centered' ? 'selected' : '' }}>Centered</option>
                                            <option value="left-aligned" {{ ($defaults['layout_style'] ?? '') == 'left-aligned' ? 'selected' : '' }}>Left Aligned</option>
                                            <option value="full-width" {{ ($defaults['layout_style'] ?? '') == 'full-width' ? 'selected' : '' }}>Full Width</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="card_style" class="form-label">Card Style</label>
                                        <select class="form-control" id="card_style" name="card_style">
                                            <option value="rounded" {{ ($defaults['card_style'] ?? 'rounded') == 'rounded' ? 'selected' : '' }}>Rounded</option>
                                            <option value="square" {{ ($defaults['card_style'] ?? '') == 'square' ? 'selected' : '' }}>Square</option>
                                            <option value="minimal" {{ ($defaults['card_style'] ?? '') == 'minimal' ? 'selected' : '' }}>Minimal</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="show_shadows" name="show_shadows" value="1" {{ ($defaults['show_shadows'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="show_shadows">Show Shadows</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="show_borders" name="show_borders" value="1" {{ ($defaults['show_borders'] ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="show_borders">Show Borders</label>
                                        </div>
                                    </div>
                                </div>

                                <h5 class="mt-4 mb-3">Sections Visibility</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="show_bio" name="show_bio" value="1" {{ ($defaults['show_bio'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="show_bio">Show Bio</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="show_socials" name="show_socials" value="1" {{ ($defaults['show_socials'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="show_socials">Show Social Links</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="show_address" name="show_address" value="1" {{ ($defaults['show_address'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="show_address">Show Address</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="show_shop" name="show_shop" value="1" {{ ($defaults['show_shop'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="show_shop">Show Shop</label>
                                            @if(auth()->user()->type === 'business')
                                                <small class="d-block text-muted">Recommended for business accounts</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <h5 class="mt-4 mb-3">Advanced</h5>
                                <div class="mb-3">
                                    <label for="custom_css" class="form-label">Custom CSS <small class="text-muted">(optional)</small></label>
                                    <textarea class="form-control" id="custom_css" name="custom_css" rows="4" placeholder=".my-class { color: red; }">{{ old('custom_css') }}</textarea>
                                    <small class="form-text text-muted">Add custom CSS to further customize your design.</small>
                                </div>

                                <div class="form-check mb-4">
                                    <input class="form-check-input" type="checkbox" id="set_as_active" name="set_as_active" value="1" checked>
                                    <label class="form-check-label" for="set_as_active">
                                        <strong>Set as active design</strong> (apply immediately to your public card)
                                    </label>
                                </div>

                                <div class="row text-right">
                                    <div class="col-12">
                                        <a href="{{ route('user.customizer') }}" class="btn btn-secondary mr-2">Cancel</a>
                                        <button type="submit" class="btn btn-primary">Save Customization</button>
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
    // Sync color pickers with text inputs
    ['primary', 'accent', 'background', 'text'].forEach(function(color) {
        var picker = document.getElementById(color + '_color_picker');
        var input = document.getElementById(color + '_color');
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
});
</script>
@endpush
