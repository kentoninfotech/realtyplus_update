<?php

namespace Database\Seeders;

use App\Models\LandingContent;
use Illuminate\Database\Seeder;

class LandingContentSeeder extends Seeder
{
    public function run()
    {
        $rows = [
            // Site settings
            ['section' => 'setting', 'key' => 'site_title', 'title' => 'RealtyPlus'],
            ['section' => 'setting', 'key' => 'site_tagline', 'title' => 'The complete platform for property managers, landlords & agents.'],
            ['section' => 'setting', 'key' => 'contact_email', 'title' => 'hello@realtyplus.com.ng'],
            ['section' => 'setting', 'key' => 'contact_phone', 'title' => '+234 800 000 0000'],

            // Hero carousel slides
            ['section' => 'hero_slide', 'sort_order' => 1, 'title' => 'Manage your real-estate portfolio in one place', 'subtitle' => 'Properties, tenants, leases, transactions — all unified.', 'cta_label' => 'Start Free Trial', 'cta_url' => '/register', 'image' => 'images/landing/hero-1.jpg'],
            ['section' => 'hero_slide', 'sort_order' => 2, 'title' => 'Built for landlords, agencies & property managers', 'subtitle' => 'Track every payment, lease, and maintenance request effortlessly.', 'cta_label' => 'See Plans', 'cta_url' => '#plans', 'image' => 'images/landing/hero-2.jpg'],
            ['section' => 'hero_slide', 'sort_order' => 3, 'title' => 'Bring your team together, securely', 'subtitle' => 'Multi-tenant data isolation, roles & permissions out of the box.', 'cta_label' => 'Get Started', 'cta_url' => '/register', 'image' => 'images/landing/hero-3.jpg'],

            // Features
            ['section' => 'feature', 'sort_order' => 1, 'icon' => 'fa-building', 'title' => 'Properties & Units', 'body' => 'Manage residential, commercial, and mixed-use properties with full unit-level detail.'],
            ['section' => 'feature', 'sort_order' => 2, 'icon' => 'fa-file-contract', 'title' => 'Leases & Tenants', 'body' => 'Digital leases, automatic renewal alerts and complete tenant histories.'],
            ['section' => 'feature', 'sort_order' => 3, 'icon' => 'fa-money-bill-wave', 'title' => 'Payments & Receipts', 'body' => 'Record rent, deposits and expenses; generate professional PDF receipts in seconds.'],
            ['section' => 'feature', 'sort_order' => 4, 'icon' => 'fa-tools', 'title' => 'Maintenance Workflow', 'body' => 'Tenants raise requests, you assign personnel — track every job through to completion.'],
            ['section' => 'feature', 'sort_order' => 5, 'icon' => 'fa-users', 'title' => 'Team & Permissions', 'body' => 'Invite personnel, assign roles, and keep each business strictly isolated from others.'],
            ['section' => 'feature', 'sort_order' => 6, 'icon' => 'fa-chart-line', 'title' => 'Reports & Insights', 'body' => 'Dashboards for occupancy, revenue, expenses and the KPIs that matter most.'],

            // Stats
            ['section' => 'stat', 'sort_order' => 1, 'title' => '500+', 'subtitle' => 'Properties Managed'],
            ['section' => 'stat', 'sort_order' => 2, 'title' => '120+', 'subtitle' => 'Businesses on Board'],
            ['section' => 'stat', 'sort_order' => 3, 'title' => '99.9%', 'subtitle' => 'Uptime'],
            ['section' => 'stat', 'sort_order' => 4, 'title' => '24/7', 'subtitle' => 'Support'],

            // Testimonials
            ['section' => 'testimonial', 'sort_order' => 1, 'title' => 'Adaeze Okeke', 'subtitle' => 'CEO, Heritage Realty', 'body' => 'RealtyPlus replaced three spreadsheets and a Whatsapp group. Our rent collection is now seamless.'],
            ['section' => 'testimonial', 'sort_order' => 2, 'title' => 'Ibrahim Musa', 'subtitle' => 'Property Manager, Lagos', 'body' => 'My team finally has one source of truth. Maintenance requests no longer fall through the cracks.'],
            ['section' => 'testimonial', 'sort_order' => 3, 'title' => 'Chinyere Eze', 'subtitle' => 'Landlord', 'body' => 'I can see who paid, who is owing and which leases expire next month — all from my phone.'],

            // FAQs
            ['section' => 'faq', 'sort_order' => 1, 'title' => 'Is there a free trial?', 'body' => 'Yes — every plan starts with a 14-day free trial. No credit card required.'],
            ['section' => 'faq', 'sort_order' => 2, 'title' => 'Is my data secure?', 'body' => 'Each business has fully isolated data. We use industry-standard encryption in transit and at rest.'],
            ['section' => 'faq', 'sort_order' => 3, 'title' => 'Can I add my team?', 'body' => 'Absolutely. Invite personnel and assign granular roles to control what each member can access.'],
            ['section' => 'faq', 'sort_order' => 4, 'title' => 'Can I upgrade or downgrade?', 'body' => 'Yes, you can change plans at any time from your business settings.'],
        ];

        foreach ($rows as $row) {
            $row['is_active'] = true;
            LandingContent::create($row);
        }
    }
}
