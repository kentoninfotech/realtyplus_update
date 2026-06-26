<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\Amenity;
use App\Models\PropertyInterest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class GuestPropertyController extends Controller
{
    /**
     * Display all available properties for guests
     */
    public function index()
    {
        $properties = Property::where('featured', true)
            ->orWhere('listing_type', '!=', null)
            ->with(['propertyType', 'images', 'amenities', 'agent'])
            ->paginate(12);

        $settings = [
            'site_title' => config('app.name'),
        ];

        return view('guest.properties', compact('properties', 'settings'));
    }

    /**
     * Display a specific property for guests
     */
    public function show($id)
    {
        $property = Property::with([
            'propertyType',
            'images',
            'amenities',
            'agent',
            'owner',
            'units'
        ])->findOrFail($id);

        $featuredImage = $property->images->firstWhere('is_featured', 1);
        $displayImage = $featuredImage ? $featuredImage->image_path : ($property->images->count() > 0 ? $property->images->first()->image_path : null);
        
        // Get all images for gallery
        $galleryImages = $property->images;

        $settings = [
            'site_title' => config('app.name'),
        ];

        return view('guest.property-detail', compact('property', 'displayImage', 'galleryImages', 'settings'));
    }

    /**
     * Submit interest form for a property
     */
    public function submitInterest(Request $request, $id)
    {
        $property = Property::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'interest_type' => 'required|in:buy,sell,rent,lease',
            'message' => 'nullable|string|max:1000',
        ]);

        try {
            // Store the interest
            $interest = new PropertyInterest();
            $interest->property_id = $property->id;
            $interest->name = $validated['name'];
            $interest->email = $validated['email'];
            $interest->phone = $validated['phone'];
            $interest->interest_type = $validated['interest_type'];
            $interest->message = $validated['message'] ?? null;
            $interest->save();

            // Send email to agent if available
            if ($property->agent && $property->agent->email) {
                try {
                    Mail::raw(
                        "New interest in property: {$property->name}\n\n" .
                        "Name: {$validated['name']}\n" .
                        "Email: {$validated['email']}\n" .
                        "Phone: {$validated['phone']}\n" .
                        "Interest Type: {$validated['interest_type']}\n" .
                        "Message: {$validated['message']}\n",
                        function ($message) use ($property) {
                            $message->to($property->agent->email)
                                ->subject("New Property Interest: {$property->name}");
                        }
                    );
                } catch (\Exception $e) {
                    // Log email error but don't fail the submission
                }
            }

            return back()->with('status', 'Thank you! Your interest has been recorded. The agent will contact you soon.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error submitting your interest. Please try again.');
        }
    }

    /**
     * Contact agent directly
     */
    public function contactAgent(Request $request, $id)
    {
        $property = Property::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'required|string|max:1000',
        ]);

        try {
            // Send email to agent
            if ($property->agent && $property->agent->email) {
                Mail::raw(
                    "Message from: {$validated['name']}\n\n" .
                    "Email: {$validated['email']}\n" .
                    "Phone: {$validated['phone']}\n\n" .
                    "Message:\n{$validated['message']}\n\n" .
                    "Regarding property: {$property->name}",
                    function ($message) use ($property, $validated) {
                        $message->to($property->agent->email)
                            ->from($validated['email'])
                            ->subject("Inquiry About Property: {$property->name}");
                    }
                );
            }

            return back()->with('status', 'Your message has been sent to the agent successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error sending your message. Please try again or contact the agent directly.');
        }
    }
}
