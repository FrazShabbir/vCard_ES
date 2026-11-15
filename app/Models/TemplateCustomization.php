<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TemplateCustomization extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'show_shadows' => 'boolean',
        'show_borders' => 'boolean',
        'show_bio' => 'boolean',
        'show_socials' => 'boolean',
        'show_address' => 'boolean',
        'show_shop' => 'boolean',
        'template_id' => 'integer',
        'design_settings' => 'array',
    ];

    /**
     * Get layout blocks (from layout_json or default). Stored as JSON in DB.
     */
    public function getLayoutBlocksAttribute()
    {
        $value = $this->attributes['layout_json'] ?? null;
        if ($value === null || $value === '') {
            return self::defaultLayoutBlocks();
        }
        $decoded = is_string($value) ? json_decode($value, true) : $value;
        return is_array($decoded) ? $decoded : self::defaultLayoutBlocks();
    }

    public function setLayoutBlocksAttribute($value)
    {
        $this->attributes['layout_json'] = is_string($value) ? $value : json_encode($value ?: []);
    }

    /**
     * Default block order and config - nothing hardcoded in views.
     */
    public static function defaultLayoutBlocks()
    {
        return [
            ['id' => 'profile_card', 'type' => 'profile_card', 'label' => 'Profile Card', 'visible' => true, 'order' => 0, 'settings' => []],
            ['id' => 'bio', 'type' => 'bio', 'label' => 'Bio', 'visible' => true, 'order' => 1, 'settings' => []],
            ['id' => 'social_links', 'type' => 'social_links', 'label' => 'Social Links', 'visible' => true, 'order' => 2, 'settings' => []],
            ['id' => 'address', 'type' => 'address', 'label' => 'Address', 'visible' => true, 'order' => 3, 'settings' => []],
            ['id' => 'shop', 'type' => 'shop', 'label' => 'Shop', 'visible' => true, 'order' => 4, 'settings' => []],
            ['id' => 'custom_sections', 'type' => 'custom_sections', 'label' => 'Custom Sections', 'visible' => true, 'order' => 5, 'settings' => []],
            ['id' => 'save_contact', 'type' => 'save_contact', 'label' => 'Save Contact', 'visible' => true, 'order' => 6, 'settings' => []],
            ['id' => 'footer', 'type' => 'footer', 'label' => 'Footer', 'visible' => true, 'order' => 7, 'settings' => []],
        ];
    }

    /**
     * Get design settings (from DB or default).
     */
    public function getDesignSettingsArray()
    {
        $stored = $this->design_settings;
        if (is_array($stored) && !empty($stored)) {
            return array_merge(self::defaultDesignSettings(), $stored);
        }
        return self::defaultDesignSettings();
    }

    public static function defaultDesignSettings()
    {
        return [
            'colors' => [
                'primary' => '#0d6efd',
                'accent' => '#198754',
                'background' => '#ffffff',
                'text' => '#212529',
                'link' => '#0d6efd',
            ],
            'typography' => [
                'heading_font' => 'Poppins',
                'body_font' => 'Poppins',
                'font_size_base' => '16px',
                'heading_size' => '2rem',
                'line_height' => '1.6',
            ],
            'layout' => [
                'style' => 'centered',
                'card_style' => 'rounded',
                'border_radius' => '12',
                'section_spacing' => '2',
                'show_shadows' => true,
                'show_borders' => false,
            ],
            'animations' => ['enabled' => false, 'style' => ''],
        ];
    }

    /**
     * Sync legacy color/font columns from design_settings (single source of truth).
     */
    public function syncColumnsFromDesignSettings(): void
    {
        $ds = $this->getDesignSettingsArray();
        $c = $ds['colors'] ?? [];
        $t = $ds['typography'] ?? [];
        $l = $ds['layout'] ?? [];

        $this->primary_color = $c['primary'] ?? $this->primary_color;
        $this->accent_color = $c['accent'] ?? $this->accent_color;
        $this->background_color = $c['background'] ?? $this->background_color;
        $this->text_color = $c['text'] ?? $this->text_color;
        $this->link_color = $c['link'] ?? $this->link_color;
        $this->heading_font = $t['heading_font'] ?? $this->heading_font;
        $this->body_font = $t['body_font'] ?? $this->body_font;
        $this->font_size_base = $t['font_size_base'] ?? $this->font_size_base;
        $this->layout_style = $l['style'] ?? $this->layout_style;
        $this->card_style = $l['card_style'] ?? $this->card_style;
        $this->border_radius = $l['border_radius'] ?? $this->border_radius;
        $this->section_spacing = $l['section_spacing'] ?? $this->section_spacing;
        $this->show_shadows = (bool) ($l['show_shadows'] ?? $this->show_shadows);
        $this->show_borders = (bool) ($l['show_borders'] ?? $this->show_borders);
    }

    /**
     * Build design_settings from legacy columns (when saving old forms).
     */
    public function syncDesignSettingsFromColumns(): void
    {
        $base = self::defaultDesignSettings();
        $this->design_settings = [
            'colors' => [
                'primary' => $this->primary_color ?? $base['colors']['primary'],
                'accent' => $this->accent_color ?? $base['colors']['accent'],
                'background' => $this->background_color ?? $base['colors']['background'],
                'text' => $this->text_color ?? $base['colors']['text'],
                'link' => $this->link_color ?? $base['colors']['link'],
            ],
            'typography' => [
                'heading_font' => $this->heading_font ?? $base['typography']['heading_font'],
                'body_font' => $this->body_font ?? $base['typography']['body_font'],
                'font_size_base' => $this->font_size_base ?? $base['typography']['font_size_base'],
                'heading_size' => $this->heading_size ?? $base['typography']['heading_size'],
                'line_height' => $this->line_height ?? $base['typography']['line_height'],
            ],
            'layout' => [
                'style' => $this->layout_style ?? $base['layout']['style'],
                'card_style' => $this->card_style ?? $base['layout']['card_style'],
                'border_radius' => $this->border_radius ?? $base['layout']['border_radius'],
                'section_spacing' => $this->section_spacing ?? $base['layout']['section_spacing'],
                'show_shadows' => (bool) ($this->show_shadows ?? true),
                'show_borders' => (bool) ($this->show_borders ?? false),
            ],
            'animations' => [
                'enabled' => (bool) ($this->enable_animations ?? false),
                'style' => $this->animation_style ?? '',
            ],
        ];
    }

    /**
     * Sanitize custom CSS: strip tags / javascript / @import for safer public pages.
     */
    public static function sanitizeCustomCss(?string $css): ?string
    {
        if ($css === null || $css === '') {
            return $css;
        }
        $css = strip_tags($css);
        $css = preg_replace('/@import\b[^;]*;/i', '', $css);
        $css = preg_replace('/expression\s*\(/i', '', $css);
        $css = preg_replace('/javascript\s*:/i', '', $css);
        $css = preg_replace('/behavior\s*:/i', '', $css);
        return $css;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get active customization for a user
     */
    public static function getActiveForUser($userId)
    {
        return static::where('user_id', $userId)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Activate this customization (deactivate others for same user)
     */
    public function activate()
    {
        static::where('user_id', $this->user_id)
            ->where('id', '!=', $this->id)
            ->update(['is_active' => false]);
        
        $this->is_active = true;
        $this->save();
    }
}
