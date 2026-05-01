<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\LandingContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LandingContentController extends Controller
{
    public function index(Request $request)
    {
        $section = $request->get('section', 'hero_slide');
        $sections = ['hero_slide', 'feature', 'testimonial', 'faq', 'stat', 'setting'];
        $items = LandingContent::where('section', $section)->orderBy('sort_order')->get();
        return view('superadmin.landing.index', compact('items', 'section', 'sections'));
    }

    public function create(Request $request)
    {
        $item = new LandingContent(['section' => $request->get('section', 'hero_slide')]);
        return view('superadmin.landing.form', compact('item'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        if ($request->hasFile('image_file')) {
            $data['image'] = $request->file('image_file')->store('landing', 'public');
        }
        LandingContent::create($data);
        return redirect()->route('superadmin.landing.index', ['section' => $data['section']])->with('status', 'Saved.');
    }

    public function edit(LandingContent $landing)
    {
        return view('superadmin.landing.form', ['item' => $landing]);
    }

    public function update(Request $request, LandingContent $landing)
    {
        $data = $this->validateData($request);
        if ($request->hasFile('image_file')) {
            if ($landing->image) {
                Storage::disk('public')->delete($landing->image);
            }
            $data['image'] = $request->file('image_file')->store('landing', 'public');
        }
        $landing->update($data);
        return redirect()->route('superadmin.landing.index', ['section' => $landing->section])->with('status', 'Saved.');
    }

    public function destroy(LandingContent $landing)
    {
        if ($landing->image) {
            Storage::disk('public')->delete($landing->image);
        }
        $section = $landing->section;
        $landing->delete();
        return redirect()->route('superadmin.landing.index', ['section' => $section])->with('status', 'Deleted.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'section'    => ['required', 'string', 'max:50'],
            'key'        => ['nullable', 'string', 'max:100'],
            'title'      => ['nullable', 'string', 'max:255'],
            'subtitle'   => ['nullable', 'string', 'max:255'],
            'body'       => ['nullable', 'string'],
            'icon'       => ['nullable', 'string', 'max:80'],
            'cta_label'  => ['nullable', 'string', 'max:80'],
            'cta_url'    => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer'],
            'is_active'  => ['nullable', 'boolean'],
            'image_file' => ['nullable', 'image', 'max:4096'],
        ]);
    }
}
