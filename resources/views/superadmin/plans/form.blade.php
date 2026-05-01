@extends('superadmin.layout')
@section('title', $plan->exists ? 'Edit plan' : 'New plan')
@section('content')
<div class="card p-4" style="max-width:800px;">
    <form method="POST" action="{{ $plan->exists ? route('superadmin.plans.update', $plan) : route('superadmin.plans.store') }}">
        @csrf
        @if($plan->exists) @method('PUT') @endif
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Name</label>
                <input class="form-control" name="name" value="{{ old('name', $plan->name) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Sort order</label>
                <input type="number" class="form-control" name="sort_order" value="{{ old('sort_order', $plan->sort_order ?? 0) }}">
            </div>
            <div class="col-12">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description" rows="2">{{ old('description', $plan->description) }}</textarea>
            </div>
            <div class="col-md-4">
                <label class="form-label">Price</label>
                <input type="number" step="0.01" class="form-control" name="price" value="{{ old('price', $plan->price ?? 0) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Currency</label>
                <input class="form-control" name="currency" value="{{ old('currency', $plan->currency ?? 'NGN') }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Billing cycle</label>
                <select class="form-select" name="billing_cycle">
                    @foreach(['monthly','quarterly','yearly','lifetime'] as $cycle)
                        <option value="{{ $cycle }}" @selected(old('billing_cycle', $plan->billing_cycle) === $cycle)>{{ ucfirst($cycle) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3"><label class="form-label">Trial days</label><input type="number" class="form-control" name="trial_days" value="{{ old('trial_days', $plan->trial_days ?? 0) }}"></div>
            <div class="col-md-3"><label class="form-label">Max users</label><input type="number" class="form-control" name="max_users" value="{{ old('max_users', $plan->max_users) }}"></div>
            <div class="col-md-3"><label class="form-label">Max properties</label><input type="number" class="form-control" name="max_properties" value="{{ old('max_properties', $plan->max_properties) }}"></div>
            <div class="col-md-3"><label class="form-label">Max personnel</label><input type="number" class="form-control" name="max_personnel" value="{{ old('max_personnel', $plan->max_personnel) }}"></div>
            <div class="col-12">
                <label class="form-label">Features (one per line)</label>
                <textarea class="form-control" name="features_text" rows="6">{{ old('features_text', is_array($plan->features) ? implode("\n", $plan->features) : '') }}</textarea>
            </div>
            <div class="col-md-6 form-check ms-2 mt-3">
                <input type="hidden" name="is_featured" value="0">
                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $plan->is_featured) ? 'checked' : '' }}>
                <label for="is_featured" class="form-check-label">Featured (most popular)</label>
            </div>
            <div class="col-md-6 form-check ms-2 mt-3">
                <input type="hidden" name="is_active" value="0">
                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $plan->is_active ?? true) ? 'checked' : '' }}>
                <label for="is_active" class="form-check-label">Active (visible publicly)</label>
            </div>
        </div>
        <div class="mt-4">
            <button class="btn btn-primary">Save plan</button>
            <a href="{{ route('superadmin.plans.index') }}" class="btn btn-link">Cancel</a>
        </div>
    </form>
</div>
@endsection
