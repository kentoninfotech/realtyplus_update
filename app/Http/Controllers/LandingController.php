<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\LandingContent;
use App\Models\Plan;
use Illuminate\Http\Request;

/**
 * Public-facing landing page controller.
 */
class LandingController extends Controller
{
    public function index()
    {
        $heroSlides   = LandingContent::section('hero_slide')->get();
        $features     = LandingContent::section('feature')->get();
        $testimonials = LandingContent::section('testimonial')->get();
        $faqs         = LandingContent::section('faq')->get();
        $stats        = LandingContent::section('stat')->get();
        $plans        = Plan::active()->orderBy('sort_order')->get();
        
        // Fetch featured properties with their images and amenities
        $featuredProperties = \App\Models\Property::featured()
            ->with(['propertyType', 'images', 'amenities', 'agent'])
            ->get();

        $settings = [
            'site_title'    => LandingContent::setting('site_title', config('app.name')),
            'site_tagline'  => LandingContent::setting('site_tagline', 'Property management, simplified.'),
            'contact_email' => LandingContent::setting('contact_email', 'hello@realtyplus.com.ng'),
            'contact_phone' => LandingContent::setting('contact_phone', '+234 800 000 0000'),
        ];

        return view('landing.welcome', compact(
            'heroSlides', 'features', 'testimonials', 'faqs', 'stats', 'plans', 'settings', 'featuredProperties'
        ));
    }

    public function feedback(Request $request)
    {
        $data = $request->validate([
            'name'    => ['required', 'string', 'max:120'],
            'email'   => ['required', 'email', 'max:160'],
            'phone'   => ['nullable', 'string', 'max:30'],
            'subject' => ['nullable', 'string', 'max:160'],
            'category'=> ['nullable', 'in:general,bug,feature,complaint,billing'],
            'message' => ['required', 'string'],
        ]);

        Feedback::create(array_merge($data, [
            'category' => $data['category'] ?? 'general',
            'status'   => 'new',
        ]));

        return back()->with('status', 'Thanks for getting in touch — we will respond shortly.');
    }
}
