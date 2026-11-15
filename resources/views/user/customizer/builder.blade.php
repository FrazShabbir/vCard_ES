@extends('user.customizer-layout')
@section('title', 'Page Builder - ' . config('app.name'))

@push('css')
<link href="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.css" rel="stylesheet">
<style>
    .builder-panels { display: flex; height: 100%; width: 100%; }
    .builder-left { width: 280px; background: #fff; border-right: 1px solid #e0e0e0; overflow-y: auto; display: flex; flex-direction: column; }
    .builder-center { flex: 1; background: #f0f0f0; display: flex; flex-direction: column; min-width: 0; }
    .builder-right { width: 300px; background: #fff; border-left: 1px solid #e0e0e0; overflow-y: auto; }
    .builder-panel-title { padding: 12px 16px; font-weight: 600; font-size: 0.9rem; border-bottom: 1px solid #eee; }
    .builder-blocks-list { padding: 12px; }
    .builder-block-item { padding: 10px 12px; margin-bottom: 6px; background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 8px; cursor: grab; display: flex; align-items: center; justify-content: space-between; font-size: 0.9rem; }
    .builder-block-item:active { cursor: grabbing; }
    .builder-block-item.sortable-ghost { opacity: 0.5; }
    .builder-block-item .visibility { cursor: pointer; color: #6c757d; }
    .builder-block-item .visibility.hidden { opacity: 0.4; }
    .builder-preview-wrap { flex: 1; position: relative; }
    .builder-preview-wrap iframe { width: 100%; height: 100%; border: none; }
    .design-section { padding: 12px 16px; border-bottom: 1px solid #eee; }
    .design-section h6 { margin: 0 0 10px; font-size: 0.85rem; color: #333; }
    .design-row { display: flex; gap: 8px; align-items: center; margin-bottom: 8px; }
    .design-row label { min-width: 90px; font-size: 0.85rem; }
    .design-row input[type="color"] { width: 36px; height: 32px; border: 1px solid #ddd; border-radius: 6px; cursor: pointer; }
    .design-row input[type="text"], .design-row select { flex: 1; padding: 6px 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 0.85rem; }
    .design-row input[type="range"] { flex: 1; }
    .builder-right-empty { padding: 24px; text-align: center; color: #999; font-size: 0.9rem; }
    #customizer-save-btn.saving { opacity: 0.7; pointer-events: none; }
</style>
@endpush

@section('content')
<div class="builder-panels">
    <aside class="builder-left">
        <div class="builder-panel-title">Blocks</div>
        <div class="builder-blocks-list" id="blocks-list">
            <p class="text-muted small">Loading…</p>
        </div>
        <div class="builder-panel-title">Design</div>
        <div class="design-section">
            <h6>Colors</h6>
            <div class="design-row">
                <label>Primary</label>
                <input type="color" id="ds_primary" value="#0d6efd">
                <input type="text" id="ds_primary_hex" value="#0d6efd" class="form-control form-control-sm">
            </div>
            <div class="design-row">
                <label>Accent</label>
                <input type="color" id="ds_accent" value="#198754">
                <input type="text" id="ds_accent_hex" value="#198754" class="form-control form-control-sm">
            </div>
            <div class="design-row">
                <label>Background</label>
                <input type="color" id="ds_background" value="#ffffff">
                <input type="text" id="ds_background_hex" value="#ffffff" class="form-control form-control-sm">
            </div>
            <div class="design-row">
                <label>Text</label>
                <input type="color" id="ds_text" value="#212529">
                <input type="text" id="ds_text_hex" value="#212529" class="form-control form-control-sm">
            </div>
        </div>
        <div class="design-section">
            <h6>Typography</h6>
            <div class="design-row">
                <label>Heading font</label>
                <select id="ds_heading_font">
                    <option value="Poppins">Poppins</option>
                    <option value="Roboto">Roboto</option>
                    <option value="Open Sans">Open Sans</option>
                    <option value="Lato">Lato</option>
                    <option value="Inter">Inter</option>
                </select>
            </div>
            <div class="design-row">
                <label>Body font</label>
                <select id="ds_body_font">
                    <option value="Poppins">Poppins</option>
                    <option value="Roboto">Roboto</option>
                    <option value="Open Sans">Open Sans</option>
                    <option value="Lato">Lato</option>
                    <option value="Inter">Inter</option>
                </select>
            </div>
            <div class="design-row">
                <label>Font size</label>
                <input type="range" id="ds_font_size" min="12" max="24" value="16">
                <span id="ds_font_size_val">16</span>px
            </div>
        </div>
        <div class="design-section">
            <h6>Layout</h6>
            <div class="design-row">
                <label>Style</label>
                <select id="ds_layout_style">
                    <option value="centered">Centered</option>
                    <option value="left-aligned">Left</option>
                    <option value="full-width">Full width</option>
                </select>
            </div>
            <div class="design-row">
                <label>Card</label>
                <select id="ds_card_style">
                    <option value="rounded">Rounded</option>
                    <option value="square">Square</option>
                    <option value="minimal">Minimal</option>
                </select>
            </div>
            <div class="design-row">
                <label>Radius</label>
                <input type="range" id="ds_border_radius" min="0" max="40" value="12">
                <span id="ds_border_radius_val">12</span>
            </div>
            <div class="design-row">
                <label><input type="checkbox" id="ds_show_shadows" checked> Shadows</label>
            </div>
            <div class="design-row">
                <label><input type="checkbox" id="ds_show_borders"> Borders</label>
            </div>
        </div>
    </aside>
    <div class="builder-center">
        <div class="builder-preview-wrap">
            <iframe id="builder-preview" src="{{ $previewUrl }}" title="Preview"></iframe>
        </div>
    </div>
    <aside class="builder-right">
        <div class="builder-panel-title">Block settings</div>
        <div id="block-settings-content" class="builder-right-empty">
            Select a block to edit its settings.
        </div>
    </aside>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
(function() {
    const customizationId = {{ $customizationId }};
    const getUrl = '{{ route("user.customizer.design.json", $customizationId) }}';
    const saveUrl = '{{ route("user.customizer.design.save", $customizationId) }}';
    const csrf = '{{ csrf_token() }}';
    let layoutBlocks = [];
    let designSettings = {};

    function loadDesign() {
        fetch(getUrl, { headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(data => {
                layoutBlocks = data.layout_blocks || [];
                designSettings = data.design_settings || {};
                renderBlocksList();
                applyDesignToForm();
                bindDesignInputs();
                initSortable();
            })
            .catch(() => {
                document.getElementById('blocks-list').innerHTML = '<p class="text-danger small">Failed to load. Try again.</p>';
            });
    }

    function applyDesignToForm() {
        const c = designSettings.colors || {};
        const t = designSettings.typography || {};
        const l = designSettings.layout || {};
        setVal('ds_primary', c.primary, '#0d6efd');
        setVal('ds_primary_hex', c.primary, '#0d6efd');
        setVal('ds_accent', c.accent, '#198754');
        setVal('ds_accent_hex', c.accent, '#198754');
        setVal('ds_background', c.background, '#ffffff');
        setVal('ds_background_hex', c.background, '#ffffff');
        setVal('ds_text', c.text, '#212529');
        setVal('ds_text_hex', c.text, '#212529');
        setVal('ds_heading_font', t.heading_font, 'Poppins');
        setVal('ds_body_font', t.body_font, 'Poppins');
        const fs = parseInt((t.font_size_base || '16').replace('px',''), 10) || 16;
        setVal('ds_font_size', fs, 16);
        document.getElementById('ds_font_size_val').textContent = fs;
        setVal('ds_layout_style', l.style, 'centered');
        setVal('ds_card_style', l.card_style, 'rounded');
        const br = parseInt(l.border_radius, 10) || 12;
        setVal('ds_border_radius', br, 12);
        document.getElementById('ds_border_radius_val').textContent = br;
        setVal('ds_show_shadows', l.show_shadows !== false, true);
        setVal('ds_show_borders', l.show_borders === true, false);
    }

    function setVal(id, val, def) {
        const el = document.getElementById(id);
        if (!el) return;
        if (el.type === 'checkbox') el.checked = val !== undefined && val !== null ? !!val : !!def;
        else el.value = val !== undefined && val !== null ? val : def;
    }

    function renderBlocksList() {
        const list = document.getElementById('blocks-list');
        list.innerHTML = layoutBlocks.map((b, i) => `
            <div class="builder-block-item" data-block-id="${b.id}" data-order="${i}">
                <span>${b.label || b.type}</span>
                <i class="visibility fas fa-eye${b.visible === false ? '-slash' : ''}" data-id="${b.id}" title="Toggle visibility"></i>
            </div>
        `).join('');
        list.querySelectorAll('.visibility').forEach(el => {
            el.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.dataset.id;
                const block = layoutBlocks.find(b => b.id === id);
                if (block) {
                    block.visible = !block.visible;
                    this.classList.toggle('fa-eye', block.visible);
                    this.classList.toggle('fa-eye-slash', !block.visible);
                    this.classList.toggle('hidden', !block.visible);
                }
            });
        });
    }

    function getDesignFromForm() {
        const c = {
            primary: document.getElementById('ds_primary_hex').value,
            accent: document.getElementById('ds_accent_hex').value,
            background: document.getElementById('ds_background_hex').value,
            text: document.getElementById('ds_text_hex').value,
            link: document.getElementById('ds_primary_hex').value
        };
        const t = {
            heading_font: document.getElementById('ds_heading_font').value,
            body_font: document.getElementById('ds_body_font').value,
            font_size_base: document.getElementById('ds_font_size').value + 'px',
            heading_size: '2rem',
            line_height: '1.6'
        };
        const l = {
            style: document.getElementById('ds_layout_style').value,
            card_style: document.getElementById('ds_card_style').value,
            border_radius: document.getElementById('ds_border_radius').value,
            show_shadows: document.getElementById('ds_show_shadows').checked,
            show_borders: document.getElementById('ds_show_borders').checked
        };
        return { colors: c, typography: t, layout: l, animations: designSettings.animations || {} };
    }

    function bindDesignInputs() {
        ['primary','accent','background','text'].forEach(name => {
            const picker = document.getElementById('ds_' + name);
            const hex = document.getElementById('ds_' + name + '_hex');
            if (picker && hex) {
                picker.addEventListener('input', () => { hex.value = picker.value; });
                hex.addEventListener('input', () => { if (/^#[0-9A-Fa-f]{6}$/.test(hex.value)) picker.value = hex.value; });
            }
        });
        document.getElementById('ds_font_size').addEventListener('input', function() {
            document.getElementById('ds_font_size_val').textContent = this.value;
        });
        document.getElementById('ds_border_radius').addEventListener('input', function() {
            document.getElementById('ds_border_radius_val').textContent = this.value;
        });
    }

    function initSortable() {
        const list = document.getElementById('blocks-list');
        if (!list || layoutBlocks.length === 0) return;
        new Sortable(list, {
            animation: 150,
            ghostClass: 'sortable-ghost',
            onEnd: function() {
                const items = list.querySelectorAll('.builder-block-item');
                layoutBlocks = Array.from(items).map((el, i) => {
                    const block = layoutBlocks.find(b => b.id === el.dataset.blockId) || {};
                    return { ...block, order: i };
                });
            }
        });
    }

    function saveDesign() {
        const btn = document.getElementById('customizer-save-btn');
        btn.classList.add('saving');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        const items = document.querySelectorAll('#blocks-list .builder-block-item');
        const blocks = Array.from(items).map((el, i) => {
            const b = layoutBlocks.find(x => x.id === el.dataset.blockId) || { id: el.dataset.blockId, type: el.dataset.blockId, label: el.textContent.trim(), visible: true, order: i, settings: {} };
            b.order = i;
            return b;
        });
        const design = getDesignFromForm();
        fetch(saveUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                _token: csrf,
                layout_blocks: blocks,
                design_settings: design
            })
        })
        .then(r => r.json())
        .then(data => {
            btn.classList.remove('saving');
            btn.innerHTML = '<i class="fas fa-save"></i> Save';
            if (data.success) {
                const iframe = document.getElementById('builder-preview');
                if (iframe) iframe.src = iframe.src;
            }
        })
        .catch(() => {
            btn.classList.remove('saving');
            btn.innerHTML = '<i class="fas fa-save"></i> Save';
        });
    }

    document.getElementById('customizer-save-btn').addEventListener('click', saveDesign);
    loadDesign();
})();
</script>
@endpush
