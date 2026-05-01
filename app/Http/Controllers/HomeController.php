<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Client;
use App\Models\projects;
use App\Models\Business;
use App\Models\Property;
use App\Models\Tenant;
use App\Models\Lease;
use App\Models\Payment;
use App\Models\MaintenanceRequest;
use App\Models\tasks;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     */
    public function index()
    {
        $user       = Auth::user();
        $businessId = $user->business_id;

        // Client view: just their own projects
        if ($user->hasRole('Client')) {
            $projects = projects::where('client_id', $user->id)
                ->with('milestones:project_id,status')
                ->get(['id', 'title', 'status']);
            $clients  = User::where('business_id', $businessId)->where('user_type', 'client')->get();
            return view('home', [
                'projects'            => $projects,
                'clients'             => $clients,
                'propertiesCount'     => 0,
                'tenantsCount'        => 0,
                'projectsCount'       => $projects->count(),
                'clientsCount'        => $clients->count(),
                'dueRents'            => collect(),
                'myTasks'             => collect(),
                'maintenanceRequests' => collect(),
                'activeProjects'      => collect(),
                'recentPayments'      => collect(),
                'totalCollectedMonth' => 0,
                'totalDueMonth'       => 0,
                'occupancyRate'       => 0,
                'totalUnits'          => 0,
                'occupiedUnits'       => 0,
            ]);
        }

        // Counters
        $propertiesCount = $this->safeCount(Property::class);
        $tenantsCount    = $this->safeCount(Tenant::class);
        $clients         = User::where('business_id', $businessId)->where('user_type', 'client')->get();
        $projects        = projects::with('milestones:project_id,status')->get(['id', 'title', 'status']);

        // Active projects
        $activeProjects = projects::with('milestones:id,project_id,status')
            ->where('status', 'In Progress')
            ->latest()
            ->take(5)
            ->get();

        // My tasks (assigned to me, not done)
        $myTasks = collect();
        if (Schema::hasTable('tasks')) {
            $myTasks = tasks::with('project:id,title')
                ->where('assigned_to', $user->id)
                ->whereNotIn('status', ['Completed', 'Done', 'Cancelled'])
                ->latest()
                ->take(8)
                ->get();
        }

        // Due rents (active leases) -- fall back gracefully if Lease/Payment tables not yet seeded
        $dueRents = collect();
        $totalCollectedMonth = 0;
        $totalDueMonth = 0;
        $recentPayments = collect();
        if (Schema::hasTable('leases')) {
            $today = Carbon::today();

            // Active leases whose rent is due (renewal_date passed OR end_date within 30 days)
            $dueRents = Lease::with(['tenant', 'property:id,name', 'propertyUnit:id,unit_number'])
                ->where('status', 'active')
                ->where(function ($q) use ($today) {
                    $q->whereDate('renewal_date', '<=', $today)
                      ->orWhereDate('end_date', '<=', $today->copy()->addDays(30));
                })
                ->orderBy('renewal_date')
                ->take(8)
                ->get();

            // monthly aggregates
            if (Schema::hasTable('payments')) {
                $totalCollectedMonth = (float) Payment::whereMonth('payment_date', now()->month)
                    ->whereYear('payment_date', now()->year)
                    ->where('status', 'paid')
                    ->sum('amount');

                $recentPayments = Payment::with('lease.tenant')
                    ->latest('payment_date')
                    ->take(5)
                    ->get();
            }

            // Sum of rent expected this month from active leases
            $totalDueMonth = (float) Lease::where('status', 'active')->sum('rent_amount');
        }

        // Maintenance requests (open)
        $maintenanceRequests = collect();
        if (Schema::hasTable('maintenance_requests')) {
            $maintenanceRequests = MaintenanceRequest::with(['property:id,name', 'propertyUnit:id,unit_number'])
                ->whereNotIn('status', ['Completed', 'Closed', 'Resolved'])
                ->orderByRaw("FIELD(priority,'Critical','High','Medium','Low')")
                ->latest()
                ->take(6)
                ->get();
        }

        // Occupancy
        $totalUnits = 0;
        $occupiedUnits = 0;
        if (Schema::hasTable('property_units')) {
            $totalUnits    = (int) \DB::table('property_units')
                ->where('business_id', $businessId)->count();
            $occupiedUnits = (int) \DB::table('property_units')
                ->where('business_id', $businessId)
                ->where('status', 'occupied')->count();
        }
        $occupancyRate = $totalUnits > 0 ? round(($occupiedUnits / $totalUnits) * 100) : 0;

        return view('home', [
            'projects'            => $projects,
            'clients'             => $clients,
            'propertiesCount'     => $propertiesCount,
            'tenantsCount'        => $tenantsCount,
            'projectsCount'       => $projects->count(),
            'clientsCount'        => $clients->count(),
            'dueRents'            => $dueRents,
            'myTasks'             => $myTasks,
            'maintenanceRequests' => $maintenanceRequests,
            'activeProjects'      => $activeProjects,
            'recentPayments'      => $recentPayments,
            'totalCollectedMonth' => $totalCollectedMonth,
            'totalDueMonth'       => $totalDueMonth,
            'occupancyRate'       => $occupancyRate,
            'totalUnits'          => $totalUnits,
            'occupiedUnits'       => $occupiedUnits,
        ]);
    }

    private function safeCount(string $modelClass): int
    {
        try {
            return (int) $modelClass::count();
        } catch (\Throwable $e) {
            return 0;
        }
    }

    public function clients()
    {
        $this->authorize('viewAny', Client::class);

        $allclients = User::where('business_id', Auth::user()->business_id)->where('user_type','client')->get();
        return view('clients')->with(['allclients'=>$allclients]);
    }



    public function newClient()
    {
        $this->authorize('create', Client::class);

        return view('new-client');
    }


    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }


    public function saveClient(Request $request)
    {
        $this->authorize('create', Client::class);

        if($request->password!=""){
            $password = Hash::make($request->password);

        }else{
            $password =$request->oldpassword;
        }

        if($request->cid>0){
            $outcome = "modified";
        }else{
            $outcome = "created";
        }

        $user = User::updateOrCreate(['id'=>$request->cid],[
            'name'         =>$request->name,
            'email'        =>$request->email,
            'password'     =>Hash::make($request->password),
            'phone_number' =>$request->phone_number,
            'user_type'     => $request->user_type ?? 'client',
            'address'      =>$request->address,
            'status'       =>$request->status,
            'business_id'  =>Auth()->user()->business_id
        ]);

        // Assign Client role to client
        $user->assignRole('Client');

        $client = Client::updateOrCreate([
            'name'         =>$request->name,
            'email'        =>$request->email,
            'about'        =>$request->about,
            'phone_number' =>$request->phone_number,
            'company_name' =>$request->company_name,
            'address'      =>$request->address,
            'business_id'  => Auth()->user()->business_id,
            'user_id'      => $user->id,
        ]);

        $message = 'The '.$request->object.' has been '.$outcome.' successfully';

        return redirect()->route('clients')->with(['message'=>$message]);
    }

    public function editClient($cid)
    {
        $this->authorize('update', Client::class);

        $client = User::where('id',$cid)->first();
        return view('edit-client')->with(['client'=>$client]);
    }

    public function updateClient(Request $request, $cid)
    {
        $this->authorize('update', Client::class);

        $user = User::findOrFail($cid);

        if($request->password!=""){
            $password = Hash::make($request->password);

        }else{
            $password =$request->oldpassword;
        }

        $user->update([
            'name'         =>$request->name,
            'email'        =>$request->email,
            'password'     =>Hash::make($request->password),
            'phone_number' =>$request->phone_number,
            'user_type'     => $request->user_type ?? 'client',
            'address'      =>$request->address,
            'status'       =>$request->status,
        ]);

        $user->client->update([
            'name'         =>$request->name,
            'email'        =>$request->email,
            'about'        =>$request->about,
            'phone_number' =>$request->phone_number,
            'company_name' =>$request->company_name,
            'address'      =>$request->address,
        ]);

        $message = 'Client updated successfully';

        return redirect()->route('clients')->with(['message'=>$message]);
    }


    public function settings(request $request){
        $validateData = $request->validate([
            'logo'=>'image|mimes:jpg,png,jpeg,gif,svg',
            'background'=>'image|mimes:jpg,png,jpeg,gif,svg'
        ]);

        if(!empty($request->file('logo'))){

            $logo = time().'.'.$request->logo->extension();

            $request->logo->move(\public_path('images'),$logo);
        }else{
            $logo = $request->oldlogo;
        }

        if(!empty($request->file('background'))){

            $background = time().'.'.$request->background->extension();

            $request->background->move(\public_path('images'),$background);
        }else{
            $background = $request->oldbackground;
        }


        Business::updateOrCreate(['id'=>$request->id],[
            'business_name' => $request->business_name,
            'motto' => $request->motto,
            'logo' => $logo,
            'address' => $request->address,
            'background' => $background,
            'primary_color'=>$request->primary_color,
            'secondary_color'=>$request->secondary_color,
            'mode'=>$request->mode,
            'deployment_type'=>$request->deployment_type,
            // 'businessgroup_id'=>$request->ministrygroup_id,
            'user_id'=>$request->user_id
        ]);


        $message = "The settings has been updated!";
        return redirect()->back()->with(['message'=>$message]);
      }

}
