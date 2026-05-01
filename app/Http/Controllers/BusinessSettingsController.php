<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\BusinessSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class BusinessSettingsController extends Controller
{
    /**
     * Settings handled by this page. Grouped purely for view rendering.
     */
    protected $textKeys = [
        // Branding
        'tag_line', 'motto',
        // Contact
        'contact_email', 'contact_phone', 'contact_phone_alt', 'website',
        'address_line1', 'address_line2', 'city', 'state', 'country', 'postal_code',
        // Social
        'social_facebook', 'social_twitter', 'social_instagram', 'social_linkedin',
        // Invoice / Receipt
        'invoice_prefix', 'receipt_prefix', 'invoice_next_number', 'receipt_next_number',
        'currency_code', 'currency_symbol', 'invoice_notes', 'receipt_notes',
        'invoice_footer', 'payment_terms', 'bank_details',
        // Tax
        'tax_name', 'tax_rate', 'tax_id_number', 'tax_inclusive',
    ];

    protected $imageKeys = [
        'header_image', 'footer_image', 'invoice_logo', 'receipt_banner', 'email_banner',
    ];

    public function edit()
    {
        $business = Business::find(Auth::user()->business_id);
        abort_unless($business, 404, 'Business not found.');

        $settings = BusinessSetting::forBusiness($business->id);

        return view('business-settings.edit', [
            'business' => $business,
            'settings' => $settings,
            'textKeys' => $this->textKeys,
            'imageKeys' => $this->imageKeys,
        ]);
    }

    public function update(Request $request)
    {
        $businessId = Auth::user()->business_id;
        $business = Business::find($businessId);
        abort_unless($business, 404, 'Business not found.');

        $rules = [
            'business_name' => 'nullable|string|max:70',
            'logo'          => 'nullable|image|mimes:jpg,jpeg,png,svg,webp|max:2048',
            'tax_rate'      => 'nullable|numeric|min:0|max:100',
            'tax_inclusive' => 'nullable|in:0,1',
            'contact_email' => 'nullable|email|max:120',
            'website'       => 'nullable|url|max:150',
        ];
        foreach ($this->imageKeys as $k) {
            $rules[$k] = 'nullable|image|mimes:jpg,jpeg,png,svg,webp|max:4096';
        }

        $validated = $request->validate($rules);

        // Update core business columns
        $coreUpdates = [];
        foreach (['business_name', 'motto', 'address', 'primary_color', 'secondary_color'] as $col) {
            if ($request->filled($col)) {
                $coreUpdates[$col] = $request->input($col);
            }
        }

        $uploadDir = public_path('images');
        if (! File::isDirectory($uploadDir)) {
            File::makeDirectory($uploadDir, 0755, true);
        }

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $name = 'biz-' . $businessId . '-logo-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $name);
            $coreUpdates['logo'] = $name;
        }

        if (! empty($coreUpdates)) {
            $business->update($coreUpdates);
        }

        // Persist text settings
        foreach ($this->textKeys as $key) {
            if ($request->has($key)) {
                BusinessSetting::set($key, $request->input($key), $businessId);
            }
        }

        // Persist image settings
        $imageDir = public_path('business/' . $businessId);
        if (! File::isDirectory($imageDir)) {
            File::makeDirectory($imageDir, 0755, true);
        }
        foreach ($this->imageKeys as $key) {
            if ($request->hasFile($key)) {
                $file = $request->file($key);
                $name = $key . '-' . time() . '.' . $file->getClientOriginalExtension();
                $file->move($imageDir, $name);
                BusinessSetting::set($key, 'business/' . $businessId . '/' . $name, $businessId);
            }
        }

        BusinessSetting::forgetCache($businessId);

        return redirect()->route('business-settings.edit')
            ->with('message', 'Business settings updated successfully.');
    }
}
