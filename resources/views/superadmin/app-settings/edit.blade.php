@extends('superadmin.layout')

@section('title','App Branding')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card p-4">
            <h5 class="fw-bold mb-3">Application Branding</h5>
            <p class="text-muted">Upload the app logo and favicon shown on the landing page, login screen, dashboards and browser tab.</p>

            <form action="{{ route('superadmin.app-settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Application Name</label>
                    <input type="text" name="app_name" class="form-control" value="{{ old('app_name', $appName) }}" maxlength="120">
                    <small class="text-muted">Shown in browser title and email subjects.</small>
                </div>

                <hr class="my-4">

                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">App Logo</label>
                        <div class="border rounded p-3 mb-2 text-center bg-light" style="min-height:120px;">
                            @if($logo && file_exists(public_path($logo)))
                                <img src="{{ asset($logo) }}?v={{ time() }}" alt="Logo" style="max-height:80px; max-width:100%;">
                            @else
                                <div class="text-muted small py-4">No logo uploaded yet</div>
                            @endif
                        </div>
                        <input type="file" name="logo" class="form-control" accept="image/*">
                        <small class="text-muted">PNG/SVG recommended. Max 2 MB.</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Favicon</label>
                        <div class="border rounded p-3 mb-2 text-center bg-light" style="min-height:120px;">
                            @if($favicon && file_exists(public_path($favicon)))
                                <img src="{{ asset($favicon) }}?v={{ time() }}" alt="Favicon" style="max-height:64px;">
                            @else
                                <div class="text-muted small py-4">No favicon uploaded yet</div>
                            @endif
                        </div>
                        <input type="file" name="favicon" class="form-control" accept="image/*,.ico">
                        <small class="text-muted">Square 32x32 / 64x64 PNG or ICO. Max 512 KB.</small>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Save Branding</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
