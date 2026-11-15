<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\CustomSection;
use App\Models\Profile;
use App\Models\TeamMember;
use App\Models\TemplateCustomization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomizerController extends Controller
{
    public function index()
    {
        return redirect()->route('user.appearance', ['tab' => 'custom']);
    }

    public function visual($id = null)
    {
        $user = auth()->user();
        $profile = $user->profile;
        
        // Get or create customization
        if ($id) {
            $customization = TemplateCustomization::where('id', $id)
                ->where('user_id', $user->id)
                ->firstOrFail();
        } else {
            $customization = TemplateCustomization::where('user_id', $user->id)
                ->where('is_active', true)
                ->first();
            
            if (!$customization) {
                // Prefer latest draft; otherwise create inactive draft (does not flip live card)
                $customization = TemplateCustomization::where('user_id', $user->id)
                    ->orderByDesc('updated_at')
                    ->first();
            }
            if (!$customization) {
                $customization = new TemplateCustomization();
                $customization->user_id = $user->id;
                $customization->template_id = (int) ($profile->template_id ?? 1);
                $customization->name = 'My Design';
                $customization->is_active = false;
                $customization->primary_color = '#0d6efd';
                $customization->accent_color = '#198754';
                $customization->background_color = '#ffffff';
                $customization->text_color = '#212529';
                $customization->link_color = '#0d6efd';
                $customization->heading_font = 'Poppins';
                $customization->body_font = 'Poppins';
                $customization->font_size_base = '16px';
                $customization->layout_style = 'centered';
                $customization->card_style = 'rounded';
                $customization->show_shadows = true;
                $customization->show_borders = false;
                $customization->design_settings = TemplateCustomization::defaultDesignSettings();
                $customization->layout_blocks = TemplateCustomization::defaultLayoutBlocks();
                $customization->save();
            }
        }
        
        // Get sections for this customization
        $sections = CustomSection::where('user_id', $user->id)
            ->where(function($query) use ($customization) {
                $query->where('customization_id', $customization->id)
                      ->orWhereNull('customization_id');
            })
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        
        // Preview URL for iframe (use dynamic view when we have layout from DB)
        $username = $user->username;
        $previewUrl = url($username) . '?preview=1&customization_id=' . $customization->id;
        
        return view('user.customizer.builder', [
            'customization' => $customization,
            'customizationId' => $customization->id,
            'sections' => $sections,
            'previewUrl' => $previewUrl,
        ]);
    }

    /**
     * API: Get layout + design as JSON (for customizer UI).
     */
    public function getDesignJson($id)
    {
        $customization = TemplateCustomization::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();
        
        $layoutBlocks = $customization->layout_blocks;
        usort($layoutBlocks, fn($a, $b) => ($a['order'] ?? 0) <=> ($b['order'] ?? 0));
        
        return response()->json([
            'layout_blocks' => $layoutBlocks,
            'design_settings' => $customization->getDesignSettingsArray(),
        ]);
    }

    /**
     * API: Save layout + design to DB (WordPress-style).
     */
    public function saveDesignJson(Request $request, $id)
    {
        $customization = TemplateCustomization::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();
        
        $request->validate([
            'layout_blocks' => 'nullable|array',
            'layout_blocks.*.id' => 'required|string',
            'layout_blocks.*.type' => 'required|string',
            'layout_blocks.*.label' => 'nullable|string',
            'layout_blocks.*.visible' => 'nullable|boolean',
            'layout_blocks.*.order' => 'nullable|integer',
            'layout_blocks.*.settings' => 'nullable|array',
            'design_settings' => 'nullable|array',
        ]);
        
        if ($request->has('layout_blocks')) {
            $customization->layout_blocks = $request->layout_blocks;
        }
        
        if ($request->has('design_settings')) {
            $customization->design_settings = $request->design_settings;
            $customization->syncColumnsFromDesignSettings();
        }
        
        $customization->save();
        
        return response()->json(['success' => true, 'message' => 'Saved']);
    }

    public function create()
    {
        $user = auth()->user();
        $profile = $user->profile;
        $baseTemplateId = (int) ($profile->template_id ?? 1);
        $baseTemplateId = max(1, min(10, $baseTemplateId));
        
        $existing = TemplateCustomization::where('user_id', $user->id)
            ->where('is_active', true)
            ->first();
        
        $defaults = [
            'template_id' => $baseTemplateId,
            'primary_color' => '#0d6efd',
            'secondary_color' => '#6c757d',
            'accent_color' => '#198754',
            'background_color' => '#ffffff',
            'text_color' => '#212529',
            'link_color' => '#0d6efd',
            'heading_font' => 'Poppins',
            'body_font' => 'Poppins',
            'font_size_base' => '16px',
            'layout_style' => 'centered',
            'card_style' => 'rounded',
            'show_shadows' => true,
            'show_borders' => false,
            'show_bio' => true,
            'show_socials' => true,
            'show_address' => true,
            'show_shop' => true,
        ];
        
        if ($existing) {
            $defaults = array_merge($defaults, $existing->only([
                'primary_color', 'secondary_color', 'accent_color', 'background_color',
                'text_color', 'link_color', 'heading_font', 'body_font', 'font_size_base',
                'layout_style', 'card_style', 'show_shadows', 'show_borders',
                'show_bio', 'show_socials', 'show_address', 'show_shop'
            ]));
        }
        
        return view('user.customizer.create', [
            'defaults' => $defaults,
            'baseTemplateId' => $baseTemplateId,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:100',
            'template_id' => 'required|integer|min:1|max:10',
            'primary_color' => 'nullable|string|max:20',
            'secondary_color' => 'nullable|string|max:20',
            'accent_color' => 'nullable|string|max:20',
            'background_color' => 'nullable|string|max:20',
            'text_color' => 'nullable|string|max:20',
            'link_color' => 'nullable|string|max:20',
            'heading_font' => 'nullable|string|max:100',
            'body_font' => 'nullable|string|max:100',
            'font_size_base' => 'nullable|string|max:20',
            'layout_style' => 'nullable|in:centered,left-aligned,full-width',
            'card_style' => 'nullable|in:rounded,square,minimal',
            'show_shadows' => 'nullable|boolean',
            'show_borders' => 'nullable|boolean',
            'show_bio' => 'nullable|boolean',
            'show_socials' => 'nullable|boolean',
            'show_address' => 'nullable|boolean',
            'show_shop' => 'nullable|boolean',
            'custom_css' => 'nullable|string|max:5000',
            'set_as_active' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();
            
            $customization = new TemplateCustomization();
            $customization->user_id = auth()->id();
            $customization->name = $request->name ?: 'Custom Design ' . date('M d');
            $customization->template_id = (int) $request->template_id;
            $customization->primary_color = $request->primary_color;
            $customization->secondary_color = $request->secondary_color;
            $customization->accent_color = $request->accent_color;
            $customization->background_color = $request->background_color;
            $customization->text_color = $request->text_color;
            $customization->link_color = $request->link_color;
            $customization->heading_font = $request->heading_font;
            $customization->body_font = $request->body_font;
            $customization->font_size_base = $request->font_size_base;
            $customization->layout_style = $request->layout_style;
            $customization->card_style = $request->card_style;
            $customization->show_shadows = (bool) $request->show_shadows;
            $customization->show_borders = (bool) $request->show_borders;
            $customization->show_bio = (bool) ($request->show_bio ?? true);
            $customization->show_socials = (bool) ($request->show_socials ?? true);
            $customization->show_address = (bool) ($request->show_address ?? true);
            $customization->show_shop = (bool) ($request->show_shop ?? true);
            $customization->custom_css = TemplateCustomization::sanitizeCustomCss($request->custom_css);
            $customization->syncDesignSettingsFromColumns();
            if (empty($customization->layout_json)) {
                $customization->layout_blocks = TemplateCustomization::defaultLayoutBlocks();
            }
            $customization->save();
            
            if ($request->set_as_active) {
                $customization->activate();
                $profile = Profile::where('user_id', auth()->id())->first();
                if ($profile) {
                    $profile->customization_id = $customization->id;
                    $profile->template_id = $customization->template_id;
                    $profile->appearance_mode = 'custom';
                    $profile->save();
                }
            }
            
            DB::commit();
            
            alert()->success('Customization saved successfully!', 'Success');
            return redirect()->route('user.appearance');
        } catch (\Throwable $th) {
            DB::rollBack();
            alert()->error('Failed to save customization.', 'Error');
            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        $customization = TemplateCustomization::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();
        
        return view('user.customizer.edit', [
            'customization' => $customization,
        ]);
    }

    public function update(Request $request, $id)
    {
        $customization = TemplateCustomization::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();
        
        $request->validate([
            'name' => 'nullable|string|max:100',
            'template_id' => 'required|integer|min:1|max:10',
            'primary_color' => 'nullable|string|max:20',
            'secondary_color' => 'nullable|string|max:20',
            'accent_color' => 'nullable|string|max:20',
            'background_color' => 'nullable|string|max:20',
            'text_color' => 'nullable|string|max:20',
            'link_color' => 'nullable|string|max:20',
            'heading_font' => 'nullable|string|max:100',
            'body_font' => 'nullable|string|max:100',
            'font_size_base' => 'nullable|string|max:20',
            'layout_style' => 'nullable|in:centered,left-aligned,full-width',
            'card_style' => 'nullable|in:rounded,square,minimal',
            'show_shadows' => 'nullable|boolean',
            'show_borders' => 'nullable|boolean',
            'show_bio' => 'nullable|boolean',
            'show_socials' => 'nullable|boolean',
            'show_address' => 'nullable|boolean',
            'show_shop' => 'nullable|boolean',
            'custom_css' => 'nullable|string|max:5000',
            'set_as_active' => 'nullable|boolean',
            'border_radius' => 'nullable|string|max:10',
            'section_spacing' => 'nullable|string|max:10',
            'heading_size' => 'nullable|string|max:10',
            'line_height' => 'nullable|string|max:10',
            'background_gradient' => 'nullable|string|max:255',
            'primary_gradient' => 'nullable|string|max:255',
            'enable_animations' => 'nullable|boolean',
            'animation_style' => 'nullable|string|max:50',
            'advanced_css' => 'nullable|string|max:10000',
        ]);

        try {
            DB::beginTransaction();
            
            $customization->name = $request->name ?: $customization->name;
            $customization->template_id = (int) $request->template_id;
            $customization->primary_color = $request->primary_color;
            $customization->secondary_color = $request->secondary_color;
            $customization->accent_color = $request->accent_color;
            $customization->background_color = $request->background_color;
            $customization->text_color = $request->text_color;
            $customization->link_color = $request->link_color;
            $customization->heading_font = $request->heading_font;
            $customization->body_font = $request->body_font;
            $customization->font_size_base = $request->font_size_base;
            $customization->layout_style = $request->layout_style;
            $customization->card_style = $request->card_style;
            $customization->show_shadows = (bool) $request->show_shadows;
            $customization->show_borders = (bool) $request->show_borders;
            $customization->show_bio = (bool) ($request->show_bio ?? true);
            $customization->show_socials = (bool) ($request->show_socials ?? true);
            $customization->show_address = (bool) ($request->show_address ?? true);
            $customization->show_shop = (bool) ($request->show_shop ?? true);
            $customization->custom_css = TemplateCustomization::sanitizeCustomCss($request->custom_css);
            $customization->border_radius = $request->border_radius;
            $customization->section_spacing = $request->section_spacing;
            $customization->heading_size = $request->heading_size;
            $customization->line_height = $request->line_height;
            $customization->background_gradient = $request->background_gradient;
            $customization->primary_gradient = $request->primary_gradient;
            $customization->enable_animations = (bool) ($request->enable_animations ?? false);
            $customization->animation_style = $request->animation_style;
            $customization->advanced_css = TemplateCustomization::sanitizeCustomCss($request->advanced_css);
            $customization->syncDesignSettingsFromColumns();
            $customization->save();
            
            if ($request->set_as_active) {
                $customization->activate();
                $profile = Profile::where('user_id', auth()->id())->first();
                if ($profile) {
                    $profile->customization_id = $customization->id;
                    $profile->template_id = $customization->template_id;
                    $profile->appearance_mode = 'custom';
                    $profile->save();
                }
            }
            
            DB::commit();
            
            alert()->success('Customization updated successfully!', 'Success');
            
            // If request is AJAX (from visual editor), return JSON
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Customization updated successfully'
                ]);
            }
            
            return redirect()->route('user.customizer');
        } catch (\Throwable $th) {
            DB::rollBack();
            alert()->error('Failed to update customization.', 'Error');
            return redirect()->back()->withInput();
        }
    }

    public function activate($id)
    {
        $customization = TemplateCustomization::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();
        
        try {
            DB::beginTransaction();
            $customization->activate();
            $profile = Profile::where('user_id', auth()->id())->first();
            if ($profile) {
                $profile->customization_id = $customization->id;
                $profile->template_id = $customization->template_id;
                $profile->appearance_mode = 'custom';
                $profile->save();
            }
            DB::commit();
            alert()->success('Customization activated! Your public card now uses this custom design.', 'Success');
        } catch (\Throwable $th) {
            DB::rollBack();
            alert()->error('Failed to activate customization.', 'Error');
        }
        
        return redirect()->route('user.appearance');
    }

    public function destroy($id)
    {
        $customization = TemplateCustomization::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();
        
        if ($customization->is_active) {
            alert()->error('Cannot delete active customization. Please activate another first.', 'Error');
            return redirect()->route('user.customizer');
        }
        
        $customization->delete();
        alert()->success('Customization deleted.', 'Success');
        return redirect()->route('user.customizer');
    }

    // Section Management Methods
    public function sections($customizationId = null)
    {
        $user = auth()->user();
        $customization = null;
        
        if ($customizationId) {
            $customization = TemplateCustomization::where('id', $customizationId)
                ->where('user_id', $user->id)
                ->firstOrFail();
        }
        
        $sections = CustomSection::where('user_id', $user->id)
            ->where(function($query) use ($customizationId) {
                if ($customizationId) {
                    $query->where('customization_id', $customizationId)
                          ->orWhereNull('customization_id');
                } else {
                    $query->whereNull('customization_id');
                }
            })
            ->orderBy('sort_order')
            ->get();
        
        return view('user.customizer.sections', [
            'sections' => $sections,
            'customization' => $customization,
            'customizationId' => $customizationId,
        ]);
    }

    public function sectionCreate($customizationId = null)
    {
        $sectionTypes = [
            'banner' => 'Hero Banner',
            'about' => 'About Section',
            'services' => 'Services',
            'team' => 'Team Members',
            'testimonials' => 'Testimonials',
            'gallery' => 'Image Gallery',
            'cta' => 'Call to Action',
            'features' => 'Features',
            'contact' => 'Contact Form',
        ];

        if (auth()->user()->type !== 'business') {
            unset($sectionTypes['team']);
        }
        
        return view('user.customizer.section-create', [
            'sectionTypes' => $sectionTypes,
            'customizationId' => $customizationId,
        ]);
    }

    public function sectionStore(Request $request, $customizationId = null)
    {
        $request->validate([
            'section_type' => 'required|in:banner,about,services,team,testimonials,gallery,cta,features,contact',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'background_color' => 'nullable|string|max:20',
            'text_color' => 'nullable|string|max:20',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'sort_order' => 'nullable|integer|min:0',
            'settings' => 'nullable|json',
        ]);

        if ($request->section_type === 'team' && auth()->user()->type !== 'business') {
            return redirect()->back()->with('error', 'Team sections are available for business accounts only.')->withInput();
        }

        try {
            DB::beginTransaction();
            
            $section = new CustomSection();
            $section->user_id = auth()->id();
            $section->customization_id = $customizationId ? (int) $customizationId : null; // Ensure null if empty
            $section->section_type = $request->section_type;
            $section->title = $request->title;
            $section->subtitle = $request->subtitle;
            $section->content = $request->content ?: null; // Store as string, not array
            $section->background_color = $request->background_color;
            $section->text_color = $request->text_color;
            $section->sort_order = (int) ($request->sort_order ?? 0);
            $section->is_active = (bool) ($request->has('is_active') ? $request->is_active : true); // Default to active
            $section->settings = $request->settings ? json_decode($request->settings, true) : null;
            
            if ($request->hasFile('background_image')) {
                $file = $request->file('background_image');
                $filename = 'section-' . time() . '-' . getRandomString() . '.' . $file->getClientOriginalExtension();
                $file->move('uploads/sections', $filename);
                $section->background_image = 'uploads/sections/' . $filename;
            }
            
            $section->save();
            
            // Debug: Log section creation (uncomment for debugging)
            // \Illuminate\Support\Facades\Log::info('Section created', [
            //     'id' => $section->id,
            //     'user_id' => $section->user_id,
            //     'customization_id' => $section->customization_id,
            //     'is_active' => $section->is_active,
            //     'section_type' => $section->section_type
            // ]);
            
            DB::commit();
            alert()->success('Section created successfully!', 'Success');
            return redirect()->route('user.customizer.sections', $customizationId);
        } catch (\Throwable $th) {
            DB::rollBack();
            alert()->error('Failed to create section.', 'Error');
            return redirect()->back()->withInput();
        }
    }

    public function sectionEdit($id)
    {
        $section = CustomSection::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();
        
        $teamMembers = [];
        if ($section->section_type === 'team') {
            $teamMembers = TeamMember::where('section_id', $section->id)
                ->orderBy('sort_order')
                ->get();
        }
        
        return view('user.customizer.section-edit', [
            'section' => $section,
            'teamMembers' => $teamMembers,
        ]);
    }

    public function sectionUpdate(Request $request, $id)
    {
        $section = CustomSection::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();
        
        $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'background_color' => 'nullable|string|max:20',
            'text_color' => 'nullable|string|max:20',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'settings' => 'nullable|json',
        ]);

        try {
            DB::beginTransaction();
            
            $section->title = $request->title;
            $section->subtitle = $request->subtitle;
            $section->content = $request->content;
            $section->background_color = $request->background_color;
            $section->text_color = $request->text_color;
            $section->sort_order = (int) ($request->sort_order ?? $section->sort_order);
            $section->is_active = (bool) ($request->is_active ?? $section->is_active);
            $section->settings = $request->settings ? json_decode($request->settings, true) : $section->settings;
            
            if ($request->hasFile('background_image')) {
                if ($section->background_image && file_exists(public_path($section->background_image))) {
                    unlink(public_path($section->background_image));
                }
                $file = $request->file('background_image');
                $filename = 'section-' . time() . '-' . getRandomString() . '.' . $file->getClientOriginalExtension();
                $file->move('uploads/sections', $filename);
                $section->background_image = 'uploads/sections/' . $filename;
            }
            
            $section->save();
            
            DB::commit();
            alert()->success('Section updated successfully!', 'Success');
            return redirect()->route('user.customizer.sections', $section->customization_id);
        } catch (\Throwable $th) {
            DB::rollBack();
            alert()->error('Failed to update section.', 'Error');
            return redirect()->back()->withInput();
        }
    }

    public function sectionDestroy($id)
    {
        $section = CustomSection::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();
        
        $customizationId = $section->customization_id;
        $section->delete();
        
        alert()->success('Section deleted.', 'Success');
        return redirect()->route('user.customizer.sections', $customizationId);
    }

    // Team Member Management
    public function teamMemberStore(Request $request, $sectionId)
    {
        if (auth()->user()->type !== 'business') {
            return redirect()->back()->with('error', 'Team members are available for business accounts only.');
        }

        $section = CustomSection::where('id', $sectionId)
            ->where('user_id', auth()->id())
            ->where('section_type', 'team')
            ->firstOrFail();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'social_links' => 'nullable|json',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        try {
            DB::beginTransaction();
            
            $member = new TeamMember();
            $member->user_id = auth()->id();
            $member->section_id = $section->id;
            $member->name = $request->name;
            $member->position = $request->position;
            $member->bio = $request->bio;
            $member->email = $request->email;
            $member->phone = $request->phone;
            $member->sort_order = (int) ($request->sort_order ?? 0);
            $member->social_links = $request->social_links ? json_decode($request->social_links, true) : null;
            
            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $filename = 'team-' . time() . '-' . getRandomString() . '.' . $file->getClientOriginalExtension();
                $file->move('uploads/team', $filename);
                $member->avatar = 'uploads/team/' . $filename;
            }
            
            $member->save();
            
            DB::commit();
            alert()->success('Team member added successfully!', 'Success');
            return redirect()->route('user.customizer.section.edit', $section->id);
        } catch (\Throwable $th) {
            DB::rollBack();
            alert()->error('Failed to add team member.', 'Error');
            return redirect()->back()->withInput();
        }
    }

    public function teamMemberDestroy($id)
    {
        $member = TeamMember::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();
        
        $sectionId = $member->section_id;
        $member->delete();
        
        alert()->success('Team member deleted.', 'Success');
        return redirect()->route('user.customizer.section.edit', $sectionId);
    }

    public function updateSectionOrder(Request $request)
    {
        $request->validate([
            'sections' => 'required|array',
            'sections.*.id' => 'required|exists:custom_sections,id',
            'sections.*.order' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();
            
            foreach ($request->sections as $item) {
                $section = CustomSection::where('id', $item['id'])
                    ->where('user_id', auth()->id())
                    ->first();
                
                if ($section) {
                    $section->sort_order = (int) $item['order'];
                    $section->save();
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Section order updated successfully'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update section order'
            ], 500);
        }
    }
}
