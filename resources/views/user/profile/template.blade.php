@extends('user.main')
@section('title', 'Choose Template - ' . config('app.name'))

@push('css')
<style>
.template-page { padding: 1rem 0; }
.template-page .card-title { margin-bottom: 1.25rem; }
.template-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem; }
.template-option { border: 2px solid #e0e0e0; border-radius: 12px; overflow: hidden; background: #fff; transition: border-color .2s, box-shadow .2s; cursor: pointer; }
.template-option:hover { border-color: #0d6efd; box-shadow: 0 4px 12px rgba(13,110,253,.15); }
.template-option.selected { border-color: #0d6efd; box-shadow: 0 0 0 2px rgba(13,110,253,.25); }
.template-option input[type="radio"] { position: absolute; opacity: 0; }
.template-preview-wrap { position: relative; width: 100%; height: 320px; background: #f5f5f5; }
.template-preview-wrap iframe { width: 100%; height: 100%; border: none; pointer-events: none; }
.template-label { padding: .75rem 1rem; font-weight: 600; font-size: .95rem; display: flex; align-items: center; gap: .5rem; }
.template-label .radio-custom { width: 20px; height: 20px; border: 2px solid #999; border-radius: 50%; flex-shrink: 0; display: flex; align-items: center; justify-content: center; }
.template-option.selected .radio-custom { border-color: #0d6efd; background: #0d6efd; }
.template-option.selected .radio-custom::after { content: ''; width: 8px; height: 8px; background: #fff; border-radius: 50%; }
.template-actions { margin-top: 1.5rem; }
</style>
@endpush

@section('content')
    <div id="content-page" class="content-page">
        <div class="container-fluid template-page">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between align-items-center flex-wrap">
                    <div class="iq-header-title">
                        <h4 class="card-title">Choose your card template</h4>
                        <p class="text-muted mb-0">Select a design for your public vCard. Preview shows how your card will look to visitors.</p>
                    </div>
                </div>
                <div class="iq-card-body">
                    <form action="{{ route('user.profile.template.save') }}" method="POST" id="template-form">
                        @csrf
                        {{ @method_field('PUT') }}
                        <div class="template-grid">
                            @foreach(range(1, 10) as $id)
                                @php $selected = ($currentTemplateId == $id); @endphp
                                <label class="template-option {{ $selected ? 'selected' : '' }}" data-template-id="{{ $id }}">
                                    <input type="radio" name="template_id" value="{{ $id }}" {{ $selected ? 'checked' : '' }}>
                                    <div class="template-preview-wrap">
                                        <iframe src="{{ $previewBaseUrl }}?preview_template={{ $id }}" title="Template {{ $id }} preview"></iframe>
                                    </div>
                                    <div class="template-label">
                                        <span class="radio-custom"></span>
                                        <span>Template {{ $id }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        <div class="template-actions">
                            <button type="submit" class="btn btn-primary">Save template</button>
                            <a href="{{ route('user.profile') }}" class="btn btn-outline-secondary ml-2">Back to profile</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    (function() {
        var options = document.querySelectorAll('.template-option');
        options.forEach(function(el) {
            el.addEventListener('click', function() {
                options.forEach(function(o) { o.classList.remove('selected'); });
                el.classList.add('selected');
                el.querySelector('input[type="radio"]').checked = true;
            });
        });
    })();
    </script>
@endsection
