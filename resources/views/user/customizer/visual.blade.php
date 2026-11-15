@extends('user.main')
@section('title', 'Visual Customizer - ' . config('app.name'))

@push('css')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
* { box-sizing: border-box; }
body { margin: 0; font-family: 'Inter', sans-serif; }
.visual-customizer { display: flex; height: calc(100vh - 60px); overflow: hidden; }
.customizer-sidebar { width: 350px; background: #fff; border-right: 1px solid #e0e0e0; overflow-y: auto; display: flex; flex-direction: column; }
.customizer-preview { flex: 1; background: #f5f5f5; overflow-y: auto; position: relative; }
.preview-frame { width: 100%; height: 100%; border: none; background: #fff; }
.sidebar-header { padding: 1.5rem; border-bottom: 1px solid #e0e0e0; background: #fff; position: sticky; top: 0; z-index: 10; }
.sidebar-header h4 { margin: 0; font-size: 1.1rem; font-weight: 600; }
.sidebar-tabs { display: flex; border-bottom: 1px solid #e0e0e0; background: #f8f9fa; }
.sidebar-tab { flex: 1; padding: 0.75rem; text-align: center; cursor: pointer; border: none; background: transparent; font-size: 0.9rem; color: #666; transition: all 0.2s; }
.sidebar-tab.active { background: #fff; color: #0d6efd; border-bottom: 2px solid #0d6efd; font-weight: 600; }
.sidebar-content { flex: 1; overflow-y: auto; padding: 1rem; }
.control-group { margin-bottom: 1.5rem; }
.control-label { display: block; margin-bottom: 0.5rem; font-size: 0.85rem; font-weight: 600; color: #333; }
.control-input { width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 6px; font-size: 0.9rem; }
.control-input:focus { outline: none; border-color: #0d6efd; box-shadow: 0 0 0 3px rgba(13,110,253,0.1); }
.color-picker-wrapper { display: flex; gap: 0.5rem; align-items: center; }
.color-picker-input { width: 50px; height: 40px; border: 1px solid #ddd; border-radius: 6px; cursor: pointer; }
.range-input { width: 100%; }
.range-value { display: inline-block; margin-left: 0.5rem; font-weight: 600; color: #0d6efd; }
.select-input { width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 6px; background: #fff; }
.preview-controls { position: absolute; top: 1rem; right: 1rem; z-index: 100; display: flex; gap: 0.5rem; }
.preview-btn { padding: 0.5rem 1rem; background: #fff; border: 1px solid #ddd; border-radius: 6px; cursor: pointer; font-size: 0.85rem; }
.preview-btn:hover { background: #f8f9fa; }
.preview-btn.active { background: #0d6efd; color: #fff; border-color: #0d6efd; }
.section-list { margin-top: 1rem; }
.section-item-preview { padding: 0.75rem; border: 1px solid #e0e0e0; border-radius: 8px; margin-bottom: 0.5rem; cursor: pointer; transition: all 0.2s; }
.section-item-preview:hover { border-color: #0d6efd; background: #f0f7ff; }
.section-item-preview.active { border-color: #0d6efd; background: #e7f3ff; }
.section-item-header { display: flex; justify-content: space-between; align-items: center; }
.section-item-title { font-weight: 600; font-size: 0.9rem; }
.section-item-type { font-size: 0.75rem; color: #666; }
.two-column { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
.three-column { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.5rem; }
.btn-save { width: 100%; padding: 0.75rem; background: #198754; color: #fff; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; margin-top: 1rem; }
.btn-save:hover { background: #157347; }
.preview-device { position: absolute; top: 1rem; left: 1rem; z-index: 100; display: flex; gap: 0.5rem; background: #fff; padding: 0.5rem; border-radius: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
.device-btn { padding: 0.4rem 0.8rem; border: 1px solid #ddd; background: #fff; border-radius: 4px; cursor: pointer; font-size: 0.8rem; }
.device-btn.active { background: #0d6efd; color: #fff; border-color: #0d6efd; }
</style>
@endpush

@section('content')
<div class="visual-customizer">
    <!-- Sidebar -->
    <div class="customizer-sidebar">
        <div class="sidebar-header">
            <h4>Customize Your vCard</h4>
            <p style="margin: 0.5rem 0 0; font-size: 0.85rem; color: #666;">Make changes and see them live</p>
        </div>
        
        <div class="sidebar-tabs">
            <button class="sidebar-tab active" data-tab="sections">Sections</button>
            <button class="sidebar-tab" data-tab="design">Design</button>
            <button class="sidebar-tab" data-tab="colors">Colors</button>
            <button class="sidebar-tab" data-tab="typography">Typography</button>
        </div>
        
        <div class="sidebar-content">
            <!-- Sections Tab -->
            <div class="tab-content active" id="tab-sections">
                <div class="control-group">
                    <label class="control-label">Manage Sections</label>
                    <a href="{{ route('user.customizer.sections', $customizationId) }}" class="btn btn-primary btn-sm" style="width: 100%; margin-bottom: 1rem;">
                        <i class="las la-layer-group"></i> Manage All Sections
                    </a>
                </div>
                
                <div class="section-list" id="sections-list">
                    @if($sections->isEmpty())
                        <p style="text-align: center; color: #999; padding: 2rem 0;">No sections yet. Add sections to customize.</p>
                    @else
                        @foreach($sections as $section)
                            <div class="section-item-preview" data-section-id="{{ $section->id }}">
                                <div class="section-item-header">
                                    <div>
                                        <div class="section-item-title">{{ $section->title ?: 'Untitled' }}</div>
                                        <div class="section-item-type">{{ ucfirst($section->section_type) }}</div>
                                    </div>
                                    <a href="{{ route('user.customizer.section.edit', $section->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="las la-edit"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
            
            <!-- Design Tab -->
            <div class="tab-content" id="tab-design" style="display: none;">
                <div class="control-group">
                    <label class="control-label">Layout Style</label>
                    <select class="select-input" id="layout_style" name="layout_style">
                        <option value="centered" {{ ($customization->layout_style ?? 'centered') == 'centered' ? 'selected' : '' }}>Centered</option>
                        <option value="left-aligned" {{ ($customization->layout_style ?? '') == 'left-aligned' ? 'selected' : '' }}>Left Aligned</option>
                        <option value="full-width" {{ ($customization->layout_style ?? '') == 'full-width' ? 'selected' : '' }}>Full Width</option>
                    </select>
                </div>
                
                <div class="control-group">
                    <label class="control-label">Card Style</label>
                    <select class="select-input" id="card_style" name="card_style">
                        <option value="rounded" {{ ($customization->card_style ?? 'rounded') == 'rounded' ? 'selected' : '' }}>Rounded</option>
                        <option value="square" {{ ($customization->card_style ?? '') == 'square' ? 'selected' : '' }}>Square</option>
                        <option value="minimal" {{ ($customization->card_style ?? '') == 'minimal' ? 'selected' : '' }}>Minimal</option>
                    </select>
                </div>
                
                <div class="control-group">
                    <label class="control-label">Border Radius <span class="range-value" id="border_radius_value">{{ $customization->border_radius ?? '12' }}</span>px</label>
                    <input type="range" class="range-input" id="border_radius" min="0" max="50" value="{{ $customization->border_radius ?? '12' }}">
                </div>
                
                <div class="control-group">
                    <label class="control-label">Section Spacing <span class="range-value" id="section_spacing_value">{{ $customization->section_spacing ?? '2' }}</span>rem</label>
                    <input type="range" class="range-input" id="section_spacing" min="0" max="5" step="0.5" value="{{ $customization->section_spacing ?? '2' }}">
                </div>
                
                <div class="control-group">
                    <label class="control-label">Background Gradient</label>
                    <select class="select-input" id="background_gradient" name="background_gradient">
                        <option value="">None (Solid Color)</option>
                        <option value="linear-gradient(135deg, #667eea 0%, #764ba2 100%)">Purple Gradient</option>
                        <option value="linear-gradient(135deg, #f093fb 0%, #f5576c 100%)">Pink Gradient</option>
                        <option value="linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)">Blue Gradient</option>
                        <option value="linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)">Green Gradient</option>
                        <option value="linear-gradient(135deg, #fa709a 0%, #fee140 100%)">Sunset Gradient</option>
                        <option value="linear-gradient(135deg, #30cfd0 0%, #330867 100%)">Ocean Gradient</option>
                    </select>
                </div>
                
                <div class="control-group">
                    <label class="control-label">Animation Style</label>
                    <select class="select-input" id="animation_style" name="animation_style">
                        <option value="">None</option>
                        <option value="fade">Fade In</option>
                        <option value="slide">Slide Up</option>
                        <option value="zoom">Zoom In</option>
                        <option value="bounce">Bounce</option>
                    </select>
                </div>
                
                <div class="control-group">
                    <label class="control-label">
                        <input type="checkbox" id="show_shadows" {{ ($customization->show_shadows ?? true) ? 'checked' : '' }}> Show Shadows
                    </label>
                </div>
                
                <div class="control-group">
                    <label class="control-label">
                        <input type="checkbox" id="show_borders" {{ ($customization->show_borders ?? false) ? 'checked' : '' }}> Show Borders
                    </label>
                </div>
                
                <div class="control-group">
                    <label class="control-label">
                        <input type="checkbox" id="enable_animations" {{ ($customization->enable_animations ?? false) ? 'checked' : '' }}> Enable Animations
                    </label>
                </div>
            </div>
            
            <!-- Colors Tab -->
            <div class="tab-content" id="tab-colors" style="display: none;">
                <div class="control-group">
                    <label class="control-label">Primary Color</label>
                    <div class="color-picker-wrapper">
                        <input type="color" class="color-picker-input" id="primary_color_picker" value="{{ $customization->primary_color ?? '#0d6efd' }}">
                        <input type="text" class="control-input" id="primary_color" value="{{ $customization->primary_color ?? '#0d6efd' }}" placeholder="#0d6efd">
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label">Accent Color</label>
                    <div class="color-picker-wrapper">
                        <input type="color" class="color-picker-input" id="accent_color_picker" value="{{ $customization->accent_color ?? '#198754' }}">
                        <input type="text" class="control-input" id="accent_color" value="{{ $customization->accent_color ?? '#198754' }}" placeholder="#198754">
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label">Background Color</label>
                    <div class="color-picker-wrapper">
                        <input type="color" class="color-picker-input" id="background_color_picker" value="{{ $customization->background_color ?? '#ffffff' }}">
                        <input type="text" class="control-input" id="background_color" value="{{ $customization->background_color ?? '#ffffff' }}" placeholder="#ffffff">
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label">Text Color</label>
                    <div class="color-picker-wrapper">
                        <input type="color" class="color-picker-input" id="text_color_picker" value="{{ $customization->text_color ?? '#212529' }}">
                        <input type="text" class="control-input" id="text_color" value="{{ $customization->text_color ?? '#212529' }}" placeholder="#212529">
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label">Link Color</label>
                    <div class="color-picker-wrapper">
                        <input type="color" class="color-picker-input" id="link_color_picker" value="{{ $customization->link_color ?? '#0d6efd' }}">
                        <input type="text" class="control-input" id="link_color" value="{{ $customization->link_color ?? '#0d6efd' }}" placeholder="#0d6efd">
                    </div>
                </div>
            </div>
            
            <!-- Typography Tab -->
            <div class="tab-content" id="tab-typography" style="display: none;">
                <div class="control-group">
                    <label class="control-label">Heading Font</label>
                    <select class="select-input" id="heading_font" name="heading_font">
                        <option value="Poppins" {{ ($customization->heading_font ?? 'Poppins') == 'Poppins' ? 'selected' : '' }}>Poppins</option>
                        <option value="Roboto" {{ ($customization->heading_font ?? '') == 'Roboto' ? 'selected' : '' }}>Roboto</option>
                        <option value="Open Sans" {{ ($customization->heading_font ?? '') == 'Open Sans' ? 'selected' : '' }}>Open Sans</option>
                        <option value="Lato" {{ ($customization->heading_font ?? '') == 'Lato' ? 'selected' : '' }}>Lato</option>
                        <option value="Montserrat" {{ ($customization->heading_font ?? '') == 'Montserrat' ? 'selected' : '' }}>Montserrat</option>
                        <option value="Inter" {{ ($customization->heading_font ?? '') == 'Inter' ? 'selected' : '' }}>Inter</option>
                        <option value="Playfair Display" {{ ($customization->heading_font ?? '') == 'Playfair Display' ? 'selected' : '' }}>Playfair Display</option>
                        <option value="Merriweather" {{ ($customization->heading_font ?? '') == 'Merriweather' ? 'selected' : '' }}>Merriweather</option>
                    </select>
                </div>
                
                <div class="control-group">
                    <label class="control-label">Body Font</label>
                    <select class="select-input" id="body_font" name="body_font">
                        <option value="Poppins" {{ ($customization->body_font ?? 'Poppins') == 'Poppins' ? 'selected' : '' }}>Poppins</option>
                        <option value="Roboto" {{ ($customization->body_font ?? '') == 'Roboto' ? 'selected' : '' }}>Roboto</option>
                        <option value="Open Sans" {{ ($customization->body_font ?? '') == 'Open Sans' ? 'selected' : '' }}>Open Sans</option>
                        <option value="Lato" {{ ($customization->body_font ?? '') == 'Lato' ? 'selected' : '' }}>Lato</option>
                        <option value="Montserrat" {{ ($customization->body_font ?? '') == 'Montserrat' ? 'selected' : '' }}>Montserrat</option>
                        <option value="Inter" {{ ($customization->body_font ?? '') == 'Inter' ? 'selected' : '' }}>Inter</option>
                    </select>
                </div>
                
                <div class="control-group">
                    <label class="control-label">Base Font Size <span class="range-value" id="font_size_value">{{ str_replace('px', '', $customization->font_size_base ?? '16') }}</span>px</label>
                    <input type="range" class="range-input" id="font_size_base" min="12" max="24" value="{{ str_replace('px', '', $customization->font_size_base ?? '16') }}">
                </div>
                
                <div class="control-group">
                    <label class="control-label">Heading Size <span class="range-value" id="heading_size_value">{{ $customization->heading_size ?? '2' }}</span>rem</label>
                    <input type="range" class="range-input" id="heading_size" min="1" max="4" step="0.1" value="{{ $customization->heading_size ?? '2' }}">
                </div>
                
                <div class="control-group">
                    <label class="control-label">Line Height <span class="range-value" id="line_height_value">{{ $customization->line_height ?? '1.6' }}</span></label>
                    <input type="range" class="range-input" id="line_height" min="1" max="2.5" step="0.1" value="{{ $customization->line_height ?? '1.6' }}">
                </div>
                
                <div class="control-group">
                    <label class="control-label">Font Weight</label>
                    <select class="select-input" id="font_weight">
                        <option value="300">Light (300)</option>
                        <option value="400" selected>Regular (400)</option>
                        <option value="500">Medium (500)</option>
                        <option value="600">Semi Bold (600)</option>
                        <option value="700">Bold (700)</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div style="padding: 1rem; border-top: 1px solid #e0e0e0; background: #fff;">
            <button class="btn-save" id="save-customization">
                <i class="las la-save"></i> Save Changes
            </button>
        </div>
    </div>
    
    <!-- Preview Area -->
    <div class="customizer-preview">
        <div class="preview-device">
            <button class="device-btn active" data-device="desktop" title="Desktop">
                <i class="las la-desktop"></i>
            </button>
            <button class="device-btn" data-device="tablet" title="Tablet">
                <i class="las la-tablet-alt"></i>
            </button>
            <button class="device-btn" data-device="mobile" title="Mobile">
                <i class="las la-mobile-alt"></i>
            </button>
        </div>
        
        <iframe id="preview-frame" class="preview-frame" src="{{ $previewUrl }}"></iframe>
    </div>
</div>
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const previewFrame = document.getElementById('preview-frame');
    const customizationId = {{ $customizationId ?? 'null' }};
    let previewUrl = '{{ $previewUrl }}';
    
    // Wait for iframe to load
    previewFrame.addEventListener('load', function() {
        updatePreview();
    });
    
    // Tab switching
    document.querySelectorAll('.sidebar-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.sidebar-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.style.display = 'none');
            this.classList.add('active');
            document.getElementById('tab-' + this.dataset.tab).style.display = 'block';
        });
    });
    
    // Device preview switching
    document.querySelectorAll('.device-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.device-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const device = this.dataset.device;
            const frame = document.getElementById('preview-frame');
            if (device === 'mobile') {
                frame.style.width = '375px';
                frame.style.margin = '0 auto';
            } else if (device === 'tablet') {
                frame.style.width = '768px';
                frame.style.margin = '0 auto';
            } else {
                frame.style.width = '100%';
                frame.style.margin = '0';
            }
        });
    });
    
    // Color picker sync
    ['primary', 'accent', 'background', 'text', 'link'].forEach(color => {
        const picker = document.getElementById(color + '_color_picker');
        const input = document.getElementById(color + '_color');
        if (picker && input) {
            picker.addEventListener('input', () => {
                input.value = picker.value;
                updatePreview();
            });
            input.addEventListener('input', () => {
                if (/^#[0-9A-F]{6}$/i.test(input.value)) {
                    picker.value = input.value;
                    updatePreview();
                }
            });
        }
    });
    
    // Range inputs
    document.querySelectorAll('.range-input').forEach(range => {
        const valueDisplay = document.getElementById(range.id + '_value');
        if (valueDisplay) {
            range.addEventListener('input', () => {
                valueDisplay.textContent = range.value;
                updatePreview();
            });
        } else {
            range.addEventListener('input', updatePreview);
        }
    });
    
    // Select inputs
    document.querySelectorAll('.select-input, input[type="checkbox"]').forEach(input => {
        input.addEventListener('change', updatePreview);
    });
    
    // Update preview function
    function updatePreview() {
        const data = {
            primary_color: document.getElementById('primary_color')?.value || '#0d6efd',
            accent_color: document.getElementById('accent_color')?.value || '#198754',
            background_color: document.getElementById('background_color')?.value || '#ffffff',
            text_color: document.getElementById('text_color')?.value || '#212529',
            link_color: document.getElementById('link_color')?.value || '#0d6efd',
            heading_font: document.getElementById('heading_font')?.value || 'Poppins',
            body_font: document.getElementById('body_font')?.value || 'Poppins',
            font_size_base: document.getElementById('font_size_base')?.value || '16',
            layout_style: document.getElementById('layout_style')?.value || 'centered',
            card_style: document.getElementById('card_style')?.value || 'rounded',
            show_shadows: document.getElementById('show_shadows')?.checked || false,
            show_borders: document.getElementById('show_borders')?.checked || false,
            border_radius: document.getElementById('border_radius')?.value || '12',
            section_spacing: document.getElementById('section_spacing')?.value || '2',
        };
        
        // Update preview frame with new styles
        if (previewFrame.contentWindow) {
            const style = previewFrame.contentWindow.document.createElement('style');
            style.id = 'customizer-preview-styles';
            const existingStyle = previewFrame.contentWindow.document.getElementById('customizer-preview-styles');
            if (existingStyle) existingStyle.remove();
            
            style.textContent = generateCSS(data);
            previewFrame.contentWindow.document.head.appendChild(style);
        }
    }
    
    function generateCSS(data) {
        const bgGradient = document.getElementById('background_gradient')?.value || '';
        const animationStyle = document.getElementById('animation_style')?.value || '';
        const enableAnimations = document.getElementById('enable_animations')?.checked || false;
        
        let animationCSS = '';
        if (enableAnimations && animationStyle) {
            if (animationStyle === 'fade') {
                animationCSS = '@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } } section { animation: fadeIn 0.6s ease-in; }';
            } else if (animationStyle === 'slide') {
                animationCSS = '@keyframes slideUp { from { transform: translateY(30px); opacity: 0; } to { transform: translateY(0); opacity: 1; } } section { animation: slideUp 0.6s ease-out; }';
            } else if (animationStyle === 'zoom') {
                animationCSS = '@keyframes zoomIn { from { transform: scale(0.9); opacity: 0; } to { transform: scale(1); opacity: 1; } } section { animation: zoomIn 0.5s ease-out; }';
            } else if (animationStyle === 'bounce') {
                animationCSS = '@keyframes bounceIn { 0% { transform: scale(0.3); opacity: 0; } 50% { transform: scale(1.05); } 70% { transform: scale(0.9); } 100% { transform: scale(1); opacity: 1; } } section { animation: bounceIn 0.8s ease-out; }';
            }
        }
        
        return `
            :root {
                --custom-primary: ${data.primary_color};
                --custom-accent: ${data.accent_color};
                --custom-bg: ${data.background_color};
                --custom-text: ${data.text_color};
                --custom-link: ${data.link_color};
            }
            body { 
                font-family: '${data.body_font}', sans-serif;
                font-size: ${data.font_size_base}px;
                ${bgGradient ? `background: ${bgGradient};` : `background-color: ${data.background_color};`}
                color: ${data.text_color};
                line-height: ${data.line_height || '1.6'};
            }
            h1, h2, h3, h4, h5, h6 { 
                font-family: '${data.heading_font}', sans-serif;
                font-size: ${data.heading_size}rem;
                font-weight: 600;
            }
            .btn-primary, button[type="submit"] { 
                background-color: ${data.primary_color};
                border-color: ${data.primary_color};
            }
            a { color: ${data.link_color}; }
            .card, .section-item { 
                border-radius: ${data.border_radius}px;
                ${data.show_shadows ? 'box-shadow: 0 2px 12px rgba(0,0,0,0.1);' : ''}
                ${data.show_borders ? `border: 2px solid ${data.primary_color};` : ''}
            }
            section { 
                margin-bottom: ${data.section_spacing}rem;
                ${enableAnimations ? 'opacity: 0;' : ''}
            }
            ${animationCSS}
        `;
    }
    
    // Save customization
    document.getElementById('save-customization').addEventListener('click', function() {
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<i class="las la-spinner la-spin"></i> Saving...';
        
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('_method', 'PUT');
        formData.append('template_id', {{ $customization->template_id ?? 1 }});
        formData.append('primary_color', document.getElementById('primary_color').value);
        formData.append('accent_color', document.getElementById('accent_color').value);
        formData.append('background_color', document.getElementById('background_color').value);
        formData.append('text_color', document.getElementById('text_color').value);
        formData.append('link_color', document.getElementById('link_color').value);
        formData.append('heading_font', document.getElementById('heading_font').value);
        formData.append('body_font', document.getElementById('body_font').value);
        formData.append('font_size_base', document.getElementById('font_size_base').value + 'px');
        formData.append('heading_size', document.getElementById('heading_size').value);
        formData.append('line_height', document.getElementById('line_height').value);
        formData.append('border_radius', document.getElementById('border_radius').value);
        formData.append('section_spacing', document.getElementById('section_spacing').value);
        formData.append('background_gradient', document.getElementById('background_gradient').value || '');
        formData.append('animation_style', document.getElementById('animation_style').value || '');
        formData.append('layout_style', document.getElementById('layout_style').value);
        formData.append('card_style', document.getElementById('card_style').value);
        formData.append('show_shadows', document.getElementById('show_shadows').checked ? '1' : '0');
        formData.append('show_borders', document.getElementById('show_borders').checked ? '1' : '0');
        formData.append('enable_animations', document.getElementById('enable_animations').checked ? '1' : '0');
        formData.append('set_as_active', '1');
        
        fetch('{{ route("user.customizer.update", $customization->id) }}', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => {
            if (response.ok) {
                return response.json();
            }
            throw new Error('Network response was not ok');
        })
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = '<i class="las la-check"></i> Saved!';
            setTimeout(() => {
                btn.innerHTML = '<i class="las la-save"></i> Save Changes';
            }, 2000);
            // Reload preview
            previewFrame.src = previewFrame.src;
        })
        .catch(error => {
            console.error('Error:', error);
            btn.disabled = false;
            btn.innerHTML = '<i class="las la-exclamation-triangle"></i> Error';
            setTimeout(() => {
                btn.innerHTML = '<i class="las la-save"></i> Save Changes';
            }, 2000);
        });
    });
    
    // Initialize preview
    setTimeout(updatePreview, 1000);
});
</script>
@endpush
