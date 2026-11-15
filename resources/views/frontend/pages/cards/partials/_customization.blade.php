{{-- Apply template customizations from design_settings (source of truth) with column fallback --}}
@if(isset($customization) && $customization)
@php
    $ds = $customization->getDesignSettingsArray();
    $c = $ds['colors'] ?? [];
    $t = $ds['typography'] ?? [];
    $l = $ds['layout'] ?? [];
    $primary = $c['primary'] ?? $customization->primary_color ?? '#0d6efd';
    $accent = $c['accent'] ?? $customization->accent_color ?? '#198754';
    $bg = $c['background'] ?? $customization->background_color ?? '#ffffff';
    $text = $c['text'] ?? $customization->text_color ?? '#212529';
    $link = $c['link'] ?? $customization->link_color ?? '#0d6efd';
    $headingFont = $t['heading_font'] ?? $customization->heading_font ?? null;
    $bodyFont = $t['body_font'] ?? $customization->body_font ?? null;
    $fontSize = $t['font_size_base'] ?? $customization->font_size_base ?? null;
    $cardStyle = $l['card_style'] ?? $customization->card_style ?? 'rounded';
    $layoutStyle = $l['style'] ?? $customization->layout_style ?? 'centered';
    $showShadows = $l['show_shadows'] ?? $customization->show_shadows ?? true;
    $showBorders = $l['show_borders'] ?? $customization->show_borders ?? false;
    $safeCss = \App\Models\TemplateCustomization::sanitizeCustomCss($customization->custom_css);
    $safeAdvanced = \App\Models\TemplateCustomization::sanitizeCustomCss($customization->advanced_css ?? null);
@endphp
<style id="template-customization-styles">
:root {
    --custom-primary: {{ $primary }};
    --custom-accent: {{ $accent }};
    --custom-bg: {{ $bg }};
    --custom-text: {{ $text }};
    --custom-link: {{ $link }};
}
@if($headingFont)
@import url('https://fonts.googleapis.com/css2?family={{ str_replace(' ', '+', $headingFont) }}:wght@400;600;700&display=swap');
body, .t3-name, .t4-name, .t5-name, .t6-name, .t7-name, .t8-name, .t9-name, .t10-name, h1, h2, h3 { font-family: '{{ $headingFont }}', sans-serif !important; }
@endif
@if($bodyFont && $bodyFont !== $headingFont)
@import url('https://fonts.googleapis.com/css2?family={{ str_replace(' ', '+', $bodyFont) }}:wght@400;500;600&display=swap');
@endif
@if($bodyFont)
body, p, .t3-bio, .t4-bio, .t5-bio, .t6-bio, .t7-bio, .t8-bio, .t9-bio, .t10-bio { font-family: '{{ $bodyFont }}', sans-serif !important; }
@endif
@if($fontSize)
body { font-size: {{ $fontSize }} !important; }
@endif
.btn-primary, .profile-shop-add, .t3-btn, .t4-btn, .t5-btn, .t6-btn, .t7-btn, .t8-btn, .t9-btn, .t10-btn { background-color: {{ $primary }} !important; border-color: {{ $primary }} !important; }
a, .profile-shop-cart-wa { color: {{ $accent }} !important; }
body, .t3-card, .t4-card, .t5-card, .t6-wrap, .t7-card, .t8-card, .t9-body, .t10-card { background-color: {{ $bg }} !important; }
body, p { color: {{ $text }} !important; }
a, .t3-link, .t4-meta a, .t6-link, .t7-social a, .t8-social a, .t9-social a, .t10-social a { color: {{ $link }} !important; }
@if($cardStyle === 'square')
.t3-card, .t4-card, .t5-card, .t6-wrap, .t7-card, .t8-card, .t9-avatar, .t10-card { border-radius: 0 !important; }
@elseif($cardStyle === 'minimal')
.t3-card, .t4-card, .t5-card, .t6-wrap, .t7-card, .t8-card, .t10-card { border: 1px solid #e0e0e0 !important; box-shadow: none !important; }
@endif
@if(!$showShadows)
.t3-card, .t4-card, .t5-card, .t6-wrap, .t7-card, .t8-card, .t10-card, .profile-shop-product { box-shadow: none !important; }
@endif
@if($showBorders)
.t3-card, .t4-card, .t5-card, .t6-wrap, .t7-card, .t8-card, .t10-card { border: 2px solid {{ $primary }} !important; }
@endif
@if($layoutStyle === 'left-aligned')
.t3-body, .t4-card, .t5-card, .t6-right, .t7-card, .t8-card, .t9-body, .t10-card { text-align: left !important; }
@elseif($layoutStyle === 'full-width')
.t3-card, .t4-card, .t5-card, .t6-wrap, .t7-card, .t8-container, .t9-body, .t10-card { max-width: 100% !important; }
@endif
@if($safeCss)
{!! $safeCss !!}
@endif
@if($safeAdvanced)
{!! $safeAdvanced !!}
@endif
</style>
@endif
