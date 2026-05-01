<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\Request;

class AppSettingsController extends Controller
{
    public function edit()
    {
        $logo    = AppSetting::get('app_logo');
        $favicon = AppSetting::get('app_favicon');
        $appName = AppSetting::get('app_name', config('app.name'));

        return view('superadmin.app-settings.edit', compact('logo', 'favicon', 'appName'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name' => ['nullable', 'string', 'max:120'],
            'logo'     => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,svg,webp', 'max:2048'],
            'favicon'  => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,svg,webp,ico', 'max:512'],
        ]);

        $dir = public_path('app');
        if (! is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }

        if ($request->filled('app_name')) {
            AppSetting::set('app_name', $request->input('app_name'));
        }

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $name = 'logo-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($dir, $name);
            AppSetting::set('app_logo', 'app/' . $name);
        }

        if ($request->hasFile('favicon')) {
            $file = $request->file('favicon');
            $name = 'favicon-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($dir, $name);
            AppSetting::set('app_favicon', 'app/' . $name);
        }

        return redirect()->route('superadmin.app-settings.edit')
            ->with('status', 'App branding updated successfully.');
    }
}
