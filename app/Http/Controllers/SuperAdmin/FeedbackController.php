<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');
        $feedback = Feedback::when($status, fn($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(20);
        return view('superadmin.feedback.index', compact('feedback', 'status'));
    }

    public function show(Feedback $feedback)
    {
        return view('superadmin.feedback.show', compact('feedback'));
    }

    public function respond(Request $request, Feedback $feedback)
    {
        $data = $request->validate([
            'admin_response' => ['required', 'string'],
            'status'         => ['required', 'in:new,in_review,responded,closed'],
        ]);
        $data['responded_at'] = now();
        $feedback->update($data);
        return back()->with('status', 'Response saved.');
    }

    public function destroy(Feedback $feedback)
    {
        $feedback->delete();
        return redirect()->route('superadmin.feedback.index')->with('status', 'Feedback deleted.');
    }
}
