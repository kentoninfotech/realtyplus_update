@extends('superadmin.layout')
@section('title', $item->exists ? 'Edit content' : 'New content')
@section('content')
<div class="card p-4" style="max-width:800px;">
    <form method="POST" action="{{ $item->exists ? route('superadmin.landing.update', $item) : route('superadmin.landing.store') }}" enctype="multipart/form-data">
        @csrf
        @if($item->exists) @method('PUT') @endif
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Section</label>
                <select class="form-select" name="section" required>
                    @foreach(['hero_slide','feature','testimonial','faq','stat','setting'] as $s)
                        <option value="{{ $s }}" @selected(old('section',$item->section)===$s)>{{ str_replace('_',' ',ucfirst($s)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3"><label class="form-label">Sort order</label><input type="number" class="form-control" name="sort_order" value="{{ old('sort_order', $item->sort_order ?? 0) }}"></div>
            <div class="col-md-3 form-check ms-2 mt-4">
                <input type="hidden" name="is_active" value="0">
                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $item->is_active ?? true) ? 'checked' : '' }}>
                <label for="is_active" class="form-check-label">Active</label>
            </div>
            <div class="col-md-6"><label class="form-label">Key (for "setting" rows)</label><input class="form-control" name="key" value="{{ old('key', $item->key) }}"></div>
            <div class="col-md-6"><label class="form-label">Icon (FA class, e.g. fa-building)</label><input class="form-control" name="icon" value="{{ old('icon', $item->icon) }}"></div>
            <div class="col-12"><label class="form-label">Title</label><input class="form-control" name="title" value="{{ old('title', $item->title) }}"></div>
            <div class="col-12"><label class="form-label">Subtitle</label><input class="form-control" name="subtitle" value="{{ old('subtitle', $item->subtitle) }}"></div>
            <div class="col-12"><label class="form-label">Body</label><textarea class="form-control" name="body" rows="3">{{ old('body', $item->body) }}</textarea></div>
            <div class="col-md-6"><label class="form-label">CTA label</label><input class="form-control" name="cta_label" value="{{ old('cta_label', $item->cta_label) }}"></div>
            <div class="col-md-6"><label class="form-label">CTA url</label><input class="form-control" name="cta_url" value="{{ old('cta_url', $item->cta_url) }}"></div>
            <div class="col-12"><label class="form-label">Image</label>
                @if($item->image)<div class="mb-2"><img src="{{ \Illuminate\Support\Facades\Storage::url($item->image) }}" style="max-height:120px;"></div>@endif
                <input type="file" class="form-control" name="image_file" accept="image/*">
            </div>
        </div>
        <div class="mt-4">
            <button class="btn btn-primary">Save</button>
            <a href="{{ route('superadmin.landing.index', ['section' => $item->section]) }}" class="btn btn-link">Cancel</a>
        </div>
    </form>
</div>
@endsection
