@extends('user.main')
@section('title', 'Edit Section - ' . config('app.name'))

@push('css')
<style>
.color-picker-group { display: flex; align-items: center; gap: .75rem; }
.color-picker-group input[type="color"] { width: 60px; height: 40px; border: 1px solid #ddd; border-radius: 6px; cursor: pointer; }
.team-members-list { margin-top: 2rem; }
.team-member-item { background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; padding: 1rem; margin-bottom: 1rem; }
.team-member-header { display: flex; justify-content: space-between; align-items: start; margin-bottom: .75rem; }
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
                                <h4 class="card-title">Edit Section: {{ $section->title ?: 'Untitled' }}</h4>
                                <p class="text-muted mb-0">Type: <strong>{{ ucfirst($section->section_type) }}</strong></p>
                            </div>
                        </div>
                        <div class="iq-card-body px-4">
                            <form action="{{ route('user.customizer.section.update', $section->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="title" class="form-label">Section Title</label>
                                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $section->title) }}" placeholder="e.g. Our Team">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="sort_order" class="form-label">Display Order</label>
                                        <input type="number" class="form-control" id="sort_order" name="sort_order" value="{{ old('sort_order', $section->sort_order) }}" min="0">
                                        <small class="form-text text-muted">Lower numbers appear first</small>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="subtitle" class="form-label">Subtitle/Description</label>
                                    <textarea class="form-control" id="subtitle" name="subtitle" rows="2" placeholder="Brief description">{{ old('subtitle', $section->subtitle) }}</textarea>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="background_color" class="form-label">Background Color</label>
                                        <div class="color-picker-group">
                                            <input type="color" id="bg_color_picker" value="{{ $section->background_color ?? '#ffffff' }}">
                                            <input type="text" class="form-control" id="background_color" name="background_color" value="{{ old('background_color', $section->background_color ?? '#ffffff') }}" placeholder="#ffffff">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="text_color" class="form-label">Text Color</label>
                                        <div class="color-picker-group">
                                            <input type="color" id="text_color_picker" value="{{ $section->text_color ?? '#212529' }}">
                                            <input type="text" class="form-control" id="text_color" name="text_color" value="{{ old('text_color', $section->text_color ?? '#212529') }}" placeholder="#212529">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="background_image" class="form-label">Background Image</label>
                                    @if($section->background_image)
                                        <div class="mb-2">
                                            <img src="{{ asset($section->background_image) }}" alt="Current background" style="max-width: 200px; border-radius: 8px;">
                                        </div>
                                    @endif
                                    <input type="file" class="form-control" id="background_image" name="background_image" accept="image/*">
                                    <small class="form-text text-muted">Leave empty to keep current image</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="content" class="form-label">Content</label>
                                    <textarea class="form-control" id="content" name="content" rows="8" placeholder="HTML content or text">{{ old('content', $section->content) }}</textarea>
                                    <small class="form-text text-muted">You can use HTML tags for formatting. For team sections, add team members below.</small>
                                </div>
                                
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $section->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Active (show on public page)</label>
                                </div>
                                
                                @if($section->section_type === 'team')
                                    <hr class="my-4">
                                    <h5 class="mb-3">Team Members</h5>
                                    
                                    @if($teamMembers->isEmpty())
                                        <p class="text-muted">No team members yet. Add your first team member below.</p>
                                    @else
                                        <div class="team-members-list">
                                            @foreach($teamMembers as $member)
                                                <div class="team-member-item">
                                                    <div class="team-member-header">
                                                        <div>
                                                            @if($member->avatar)
                                                                <img src="{{ asset($member->avatar) }}" alt="{{ $member->name }}" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; margin-right: 1rem; display: inline-block; vertical-align: middle;">
                                                            @endif
                                                            <strong>{{ $member->name }}</strong>
                                                            @if($member->position)
                                                                <span class="text-muted"> - {{ $member->position }}</span>
                                                            @endif
                                                        </div>
                                                        <form action="{{ route('user.customizer.team.delete', $member->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete this team member?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm">
                                                                <i class="las la-trash"></i> Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                    @if($member->bio)
                                                        <p class="mb-0">{{ Str::limit($member->bio, 150) }}</p>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    
                                    <div class="mt-4">
                                        <h6>Add New Team Member</h6>
                                        <form action="{{ route('user.customizer.team.store', $section->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="member_name" class="form-label">Name *</label>
                                                    <input type="text" class="form-control" id="member_name" name="name" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="member_position" class="form-label">Position</label>
                                                    <input type="text" class="form-control" id="member_position" name="position" placeholder="e.g. CEO, Designer">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="member_bio" class="form-label">Bio</label>
                                                <textarea class="form-control" id="member_bio" name="bio" rows="2"></textarea>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="member_email" class="form-label">Email</label>
                                                    <input type="email" class="form-control" id="member_email" name="email">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="member_phone" class="form-label">Phone</label>
                                                    <input type="text" class="form-control" id="member_phone" name="phone">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="member_avatar" class="form-label">Avatar</label>
                                                <input type="file" class="form-control" id="member_avatar" name="avatar" accept="image/*">
                                            </div>
                                            <button type="submit" class="btn btn-primary">Add Team Member</button>
                                        </form>
                                    </div>
                                @endif
                                
                                <hr class="my-4">
                                
                                <div class="row text-right">
                                    <div class="col-12">
                                        <a href="{{ route('user.customizer.sections', $section->customization_id) }}" class="btn btn-secondary mr-2">Cancel</a>
                                        <button type="submit" class="btn btn-primary">Update Section</button>
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
