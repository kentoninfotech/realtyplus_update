@extends('superadmin.layout')
@section('title', $item->exists ? 'Edit content' : 'New content')
@section('content')

<style>
    .form-help { font-size: 0.85rem; color: #6c757d; margin-top: 4px; font-style: italic; }
    .section-badge { display: inline-block; padding: 6px 12px; background: #e7f3ff; border: 1px solid #b3d9ff; border-radius: 4px; margin-bottom: 20px; }
    .section-badge strong { color: #0056b3; }
    .field-group { border-left: 3px solid #e9ecef; padding-left: 16px; margin-bottom: 24px; }
    .field-group.active { border-left-color: #0056b3; }
    .field-group-title { font-weight: 600; color: #495057; font-size: 0.95rem; margin-bottom: 12px; display: flex; align-items: center; }
    .field-group-title i { margin-right: 8px; color: #0056b3; }
    .info-box { background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 6px; padding: 12px; margin-bottom: 20px; font-size: 0.9rem; }
    .info-box.info { border-left: 4px solid #0056b3; }
    .info-box.warning { border-left: 4px solid #ffc107; }
    .tip-label { background: #ffeaa7; color: #744210; padding: 4px 8px; border-radius: 3px; font-size: 0.75rem; font-weight: 600; margin-left: 8px; }
</style>

<div class="card p-4" style="max-width:900px;">
    {{-- Content Type Indicator --}}
    @if($item->exists)
        @php
            $sectionLabels = [
                'hero_slide' => 'Hero Slides - Large banner images with headlines at page top',
                'feature' => 'Features - Icon + title + description cards',
                'testimonial' => 'Testimonials - Customer quotes and reviews',
                'faq' => 'FAQ - Questions and answers',
                'stat' => 'Statistics - Numbers with labels (team size, projects, etc)',
                'setting' => 'Settings - Configuration values (email, phone, etc)'
            ];
        @endphp
        <div class="section-badge">
            <i class="fas fa-info-circle"></i>
            <strong>Editing {{ str_replace('_', ' ', ucfirst($item->section)) }}:</strong>
            {{ $sectionLabels[$item->section] }}
        </div>
    @endif

    <form method="POST" action="{{ $item->exists ? route('superadmin.landing.update', $item) : route('superadmin.landing.store') }}" enctype="multipart/form-data">
        @csrf
        @if($item->exists) @method('PUT') @endif

        {{-- Content Type & Position --}}
        <div class="field-group {{ !$item->exists ? 'active' : '' }}">
            <div class="field-group-title"><i class="fas fa-folder"></i>Content Type & Position</div>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">
                        Section <span class="text-danger">*</span>
                        @if(!$item->exists) <span class="tip-label">REQUIRED</span> @endif
                    </label>
                    <select class="form-select" name="section" required>
                        <option value="">-- Select Section --</option>
                        @foreach(['hero_slide','feature','testimonial','faq','stat','setting'] as $s)
                            <option value="{{ $s }}" @selected(old('section',$item->section)===$s)>{{ str_replace('_',' ',ucfirst($s)) }}</option>
                        @endforeach
                    </select>
                    @if($item->exists)
                        <input type="hidden" name="section" value="{{ $item->section }}">
                    @endif
                    <div class="form-help">
                        📍 <strong>Choose where this content appears:</strong> Hero Slide, Feature, Testimonial, FAQ, Stat, or Settings
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="form-label">
                        Sort Order
                        <span class="tip-label">POSITION</span>
                    </label>
                    <input type="number" class="form-control" name="sort_order" value="{{ old('sort_order', $item->sort_order ?? 0) }}" placeholder="0">
                    <div class="form-help">
                        🔢 Lower numbers appear first. E.g., 1, 2, 3...
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-check" style="margin-top: 30px;">
                        <input type="hidden" name="is_active" value="0">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $item->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active" style="font-weight: 600;">
                            ✓ Active / Show
                        </label>
                    </div>
                    <div class="form-help">
                        👁️ Uncheck to hide this content from the page
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="field-group active">
            <div class="field-group-title"><i class="fas fa-pen"></i>Main Content</div>

            <div class="mb-3">
                <label class="form-label">
                    Title <span class="text-danger">*</span>
                </label>
                <input class="form-control" name="title" value="{{ old('title', $item->title) }}" placeholder="E.g., 'Welcome to RealtyPlus'" required>
                <div class="form-help">
                    📝 Main heading - appears prominently on the page
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Subtitle</label>
                <input class="form-control" name="subtitle" value="{{ old('subtitle', $item->subtitle) }}" placeholder="E.g., 'Find your perfect property'">
                <div class="form-help">
                    💬 Secondary heading or short description below the title
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Body / Description</label>
                <textarea class="form-control" name="body" rows="4" placeholder="Detailed content, features, or full text...">{{ old('body', $item->body) }}</textarea>
                <div class="form-help">
                    📄 Longer content. Use for descriptions, testimonials, FAQ answers, etc.
                </div>
            </div>
        </div>

        {{-- Optional Fields --}}
        <div class="field-group">
            <div class="field-group-title"><i class="fas fa-sliders-h"></i>Optional Elements</div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">
                        Icon (FontAwesome class)
                        <span class="tip-label">FEATURES & STATS ONLY</span>
                    </label>
                    <div class="input-group">
                        <input class="form-control" name="icon" value="{{ old('icon', $item->icon) }}" placeholder="fa-building">
                        <span class="input-group-text">
                            <i class="{{ old('icon', $item->icon) ?? 'fas fa-question' }}"></i>
                        </span>
                    </div>
                    <div class="form-help">
                        🎨 FontAwesome icon class. Examples: <code>fa-home</code>, <code>fa-users</code>, <code>fa-check</code>
                        <br><a href="https://fontawesome.com/icons" target="_blank">Browse icons →</a>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">
                        Key (Settings only)
                        <span class="tip-label">SETTINGS ROWS</span>
                    </label>
                    <input class="form-control" name="key" value="{{ old('key', $item->key) }}" placeholder="e.g., phone, email, address">
                    <div class="form-help">
                        🔑 Unique identifier for settings (phone, email, fax, address, etc.)
                    </div>
                </div>
            </div>
        </div>

        {{-- Call-to-Action --}}
        <div class="field-group">
            <div class="field-group-title"><i class="fas fa-external-link-alt"></i>Call-to-Action Button (Optional)</div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Button Label</label>
                    <input class="form-control" name="cta_label" value="{{ old('cta_label', $item->cta_label) }}" placeholder="E.g., 'Learn More', 'Get Started'">
                    <div class="form-help">
                        📌 Text displayed on the button
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Button URL</label>
                    <input class="form-control" name="cta_url" value="{{ old('cta_url', $item->cta_url) }}" placeholder="https://example.com or /page">
                    <div class="form-help">
                        🔗 Where the button links to. Use full URL or relative path.
                    </div>
                </div>
            </div>
        </div>

        {{-- Image Upload --}}
        <div class="field-group">
            <div class="field-group-title"><i class="fas fa-image"></i>Featured Image</div>

            @if($item->image)
                <div class="mb-3">
                    <p class="text-muted mb-2">Current Image:</p>
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($item->image) }}" style="max-height:180px; border-radius: 6px; border: 1px solid #dee2e6;">
                </div>
            @endif

            <div class="mb-3">
                <label class="form-label">Upload New Image</label>
                <input type="file" class="form-control" name="image_file" accept="image/*">
                <div class="form-help">
                    🖼️ Supported: JPG, PNG, WebP. Recommended: Min 1200x600px for hero slides
                </div>
            </div>
        </div>

        {{-- Info Box --}}
        <div class="info-box info">
            <strong>💡 Pro Tip:</strong> Use sort order to arrange items. The section you're editing is <strong>"{{ str_replace('_', ' ', ucfirst($item->section ?? 'not selected')) }}"</strong>. 
            @if($item->exists)
                Your changes will only appear in this section—not in other tabs.
            @endif
        </div>

        {{-- Form Actions --}}
        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-2"></i>{{ $item->exists ? 'Update' : 'Create' }} Content
            </button>
            <a href="{{ route('superadmin.landing.index', ['section' => $item->section ?? 'hero_slide']) }}" class="btn btn-secondary">
                <i class="fas fa-times me-2"></i>Cancel
            </a>
        </div>
    </form>
</div>

@endsection
