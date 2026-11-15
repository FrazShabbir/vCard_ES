<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomSection extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customization()
    {
        return $this->belongsTo(TemplateCustomization::class);
    }

    public function teamMembers()
    {
        return $this->hasMany(TeamMember::class, 'section_id')->orderBy('sort_order');
    }

    /**
     * Get sections for a user and optional customization
     */
    public static function getForUser($userId, $customizationId = null)
    {
        $query = static::where('user_id', $userId)
            ->where('is_active', true);
        
        // If customization ID is provided, show sections for that customization OR global sections
        if ($customizationId) {
            $query->where(function($q) use ($customizationId) {
                $q->where('customization_id', $customizationId)
                  ->orWhereNull('customization_id');
            });
        } else {
            // If no customization ID provided, show:
            // 1. Global sections (customization_id is null)
            // 2. Sections linked to user's active customization (if exists)
            // 3. Sections linked to user's profile customization_id (if exists)
            $activeCustomization = TemplateCustomization::getActiveForUser($userId);
            $profile = \App\Models\Profile::where('user_id', $userId)->first();
            $profileCustomizationId = $profile && $profile->customization_id ? $profile->customization_id : null;
            
            $query->where(function($q) use ($activeCustomization, $profileCustomizationId) {
                $q->whereNull('customization_id'); // Always include global sections
                
                if ($activeCustomization) {
                    $q->orWhere('customization_id', $activeCustomization->id);
                }
                
                if ($profileCustomizationId && $profileCustomizationId !== ($activeCustomization ? $activeCustomization->id : null)) {
                    $q->orWhere('customization_id', $profileCustomizationId);
                }
            });
        }
        
        return $query->with('teamMembers')->orderBy('sort_order')->get();
    }
}
