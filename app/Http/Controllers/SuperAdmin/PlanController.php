<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::orderBy('sort_order')->paginate(20);
        return view('superadmin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('superadmin.plans.form', ['plan' => new Plan()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['name']) . '-' . Str::random(4);
        $data['features'] = $this->splitFeatures($request->input('features_text'));
        Plan::create($data);
        return redirect()->route('superadmin.plans.index')->with('status', 'Plan created.');
    }

    public function edit(Plan $plan)
    {
        return view('superadmin.plans.form', compact('plan'));
    }

    public function update(Request $request, Plan $plan)
    {
        $data = $this->validated($request);
        $data['features'] = $this->splitFeatures($request->input('features_text'));
        $plan->update($data);
        return redirect()->route('superadmin.plans.index')->with('status', 'Plan updated.');
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();
        return back()->with('status', 'Plan deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name'           => ['required', 'string', 'max:120'],
            'description'    => ['nullable', 'string'],
            'price'          => ['required', 'numeric', 'min:0'],
            'currency'       => ['required', 'string', 'max:8'],
            'billing_cycle'  => ['required', 'in:monthly,quarterly,yearly,lifetime'],
            'trial_days'     => ['nullable', 'integer', 'min:0'],
            'max_users'      => ['nullable', 'integer', 'min:0'],
            'max_properties' => ['nullable', 'integer', 'min:0'],
            'max_personnel'  => ['nullable', 'integer', 'min:0'],
            'is_featured'    => ['nullable', 'boolean'],
            'is_active'      => ['nullable', 'boolean'],
            'sort_order'     => ['nullable', 'integer'],
        ]);
    }

    private function splitFeatures(?string $text): array
    {
        if (! $text) return [];
        return array_values(array_filter(array_map('trim', preg_split("/\r\n|\n|\r/", $text))));
    }
}
