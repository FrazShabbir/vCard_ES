<!DOCTYPE html>
<html lang="en">
<head>
    @include('frontend.pages.cards.partials._meta')
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @php
        $ds = $customization ? $customization->getDesignSettingsArray() : \App\Models\TemplateCustomization::defaultDesignSettings();
        $c = $ds['colors'] ?? [];
        $t = $ds['typography'] ?? [];
        $l = $ds['layout'] ?? [];
        $headingFont = $t['heading_font'] ?? 'Poppins';
        $bodyFont = $t['body_font'] ?? 'Poppins';
        $safeCss = $customization ? \App\Models\TemplateCustomization::sanitizeCustomCss($customization->custom_css) : null;
        $safeAdvanced = $customization ? \App\Models\TemplateCustomization::sanitizeCustomCss($customization->advanced_css ?? null) : null;
    @endphp
    @if($headingFont)
    <link href="https://fonts.googleapis.com/css2?family={{ urlencode($headingFont) }}:wght@400;600;700&display=swap" rel="stylesheet">
    @endif
    @if($bodyFont && $bodyFont !== $headingFont)
    <link href="https://fonts.googleapis.com/css2?family={{ urlencode($bodyFont) }}:wght@400;500;600&display=swap" rel="stylesheet">
    @endif
    <style id="design-styles">
        :root {
            --primary: {{ $c['primary'] ?? '#0d6efd' }};
            --accent: {{ $c['accent'] ?? '#198754' }};
            --bg: {{ $c['background'] ?? '#ffffff' }};
            --text: {{ $c['text'] ?? '#212529' }};
            --link: {{ $c['link'] ?? '#0d6efd' }};
            --radius: {{ $l['border_radius'] ?? '12' }}px;
            --section-gap: {{ $l['section_spacing'] ?? '2' }}rem;
        }
        * { box-sizing: border-box; }
        html { -webkit-text-size-adjust: 100%; }
        body {
            font-family: '{{ $bodyFont }}', sans-serif;
            font-size: {{ $t['font_size_base'] ?? '16px' }};
            color: var(--text);
            background: var(--bg);
            line-height: {{ $t['line_height'] ?? '1.6' }};
            margin: 0;
            padding: 1rem;
            overflow-x: hidden;
        }
        h1, h2, h3, h4, h5, h6 { font-family: '{{ $headingFont }}', sans-serif; }
        a { color: var(--link); }
        img { max-width: 100%; height: auto; }
        .card, .profile-card {
            border-radius: var(--radius);
            {{ ($l['show_shadows'] ?? true) ? 'box-shadow: 0 2px 12px rgba(0,0,0,0.08);' : 'box-shadow: none;' }}
            {{ ($l['show_borders'] ?? false) ? 'border: 2px solid var(--primary);' : '' }}
        }
        section { margin-bottom: var(--section-gap); }
        .btn-primary { background: var(--primary); border-color: var(--primary); min-height: 44px; }
        @if($safeCss)
        {!! $safeCss !!}
        @endif
        @if($safeAdvanced)
        {!! $safeAdvanced !!}
        @endif
    </style>
</head>
<body>
    <div class="container {{ ($l['style'] ?? '') === 'full-width' ? 'container-fluid' : '' }} py-4" style="max-width: {{ ($l['style'] ?? '') === 'full-width' ? '100%' : '720px' }};">
        @php
            $blocks = $customization ? $customization->layout_blocks : \App\Models\TemplateCustomization::defaultLayoutBlocks();
            usort($blocks, fn($a, $b) => ($a['order'] ?? 0) <=> ($b['order'] ?? 0));
            // Honor show_* flags from customization columns
            $showMap = [
                'bio' => $customization->show_bio ?? true,
                'social_links' => $customization->show_socials ?? true,
                'address' => $customization->show_address ?? true,
                'shop' => $customization->show_shop ?? true,
            ];
        @endphp
        @foreach($blocks as $block)
            @if(($block['visible'] ?? true) === false)
                @continue
            @endif
            @php $type = $block['type'] ?? ''; @endphp
            @if(isset($showMap[$type]) && !$showMap[$type])
                @continue
            @endif
            @switch($type)
                @case('profile_card')
                    @include('frontend.pages.cards.blocks.profile_card')
                    @break
                @case('bio')
                    @include('frontend.pages.cards.blocks.bio')
                    @break
                @case('social_links')
                    @include('frontend.pages.cards.blocks.social_links')
                    @break
                @case('address')
                    @include('frontend.pages.cards.blocks.address')
                    @break
                @case('shop')
                    @include('frontend.pages.cards.blocks.shop')
                    @break
                @case('custom_sections')
                    @include('frontend.pages.cards.blocks.custom_sections')
                    @break
                @case('save_contact')
                    @include('frontend.pages.cards.blocks.save_contact')
                    @break
                @case('footer')
                    @include('frontend.pages.cards.blocks.footer')
                    @break
                @default
            @endswitch
        @endforeach
    </div>
</body>
</html>
