<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Personnel;
use App\Http\Requests\CreatePersonnelRequest;
use App\Http\Requests\UpdatePersonnelRequest;
use Illuminate\Support\Facades\DB;

class PersonnelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Show all Personnel (Staff,Workers,Contractors).
     **
     */
    public function index()
    {
        // Check if the user has permission to view personnel
        $this->authorize('view', User::class);
        
        $users = User::where('business_id', auth()->user()->business_id)->where('user_type', '!=', 'client')->paginate(10);
        return view('personnel.index', compact('users'));
    }

    /**
     * Show add new Personnel (Staff,Workers,Contractors) form.
     **
     */
    public function newPersonnel()
    {
        // Check if the user has permission to create personnel
        $this->authorize('create', User::class);

        $roles = Role::whereNotIn('name', ['Super Admin', 'Client'])->get();
        return view('personnel.new-personnel', compact('roles'));
    }

    /**
     * Show Personnel (Staff,Workers,Contractors) form.
     **
     */
    public function showPersonnel($id)
    {
        $this->authorize('view', User::class);

        $user = User::findOrFail($id);
        return view('personnel.personnel', compact('user'));
    }

    /**
     * Show All Staffs.
     **
     */
    public function allStaffs()
    {
        $this->authorize('view', User::class);

        $staffs = User::where('business_id', auth()->user()->business_id)->where('user_type', 'staff')->paginate(20);
        return view('personnel.staffs', compact('staffs'));
    }
    /**
     * Show All Workers.
     **
     */
    public function allWorkers()
    {
        $this->authorize('view', User::class);

        $workers = User::where('business_id', auth()->user()->business_id)->where('user_type', 'worker')->paginate(20);
        return view('personnel.workers', compact('workers'));
    }
    /**
     * Show All Contractors.
     **
     */
    public function allContractors()
    {
        $this->authorize('view', User::class);

        $contractors = User::where('business_id', auth()->user()->business_id)->where('user_type', 'contractor')->paginate(20);
        return view('personnel.contractors', compact('contractors'));
    }

    /**
     * Create new Personnel (Staff,Workers,Contractors).
     **
     */
    public function createPersonnel(CreatePersonnelRequest $request)
    {
        // Check if the user has permission to create personnel
        $this->authorize('create', User::class);

        DB::transaction(function () use ($request) {
            $validateData = $request->all();

            $user = User::create([
                'name' => $validateData['first_name'] . ' ' . $validateData['last_name'],
                'email' => $validateData['email'],
                'password' => bcrypt($validateData['password']),
                'phone_number' => $validateData['phone_number'] ?? null,
                'business_id' => auth()->user()->business_id,
                'status' => 'active',
                'user_type' => $validateData['user_type'] ?? 'staff',
            ]);

            // Assign role
            $role = Role::find($validateData['role']);
            if ($role) {
                $user->assignRole($role->name);
            }

            $validateData['business_id'] = auth()->user()->business_id;
            $validateData['user_id'] = $user->id;
            // Generate unique staff ID // THIS IS BEING HANDLED IN THE MODEL
            // $validateData['staff_id'] = Personnel::generateUniqueStaffId();

            // file uploads if they exist
            if ($request->hasFile('picture')) {
                $file = $request->file('picture');
                $datePrefix = date('d-m-Y');
                $filename = $datePrefix . '_' . $file->getClientOriginalName();
                $file->move(public_path('personnel/pictures'), $filename);
                $validateData['picture'] = $filename;
            }
            if ($request->hasFile('cv')) {
                $file = $request->file('cv');
                $datePrefix = date('d-m-Y');
                $filename = $datePrefix . '_' . $file->getClientOriginalName();
                $file->move(public_path('personnel/cv'), $filename);
                $validateData['cv'] = $filename;
            }

            // Remove fields not needed for Personnel
            unset($validateData['password'], $validateData['role'], $validateData['user_type'],);
            // Create personnel record
            Personnel::create($validateData);
        });

        return redirect()->route('personnel')->with('message', 'Personnel created successfully.');
        
    }

    /**
     * Show Edit Personnel (Staff,Workers,Contractors).
     **
     */
    public function editPersonnel($id)
    {
        $this->authorize('update', User::class);

        $user = User::findOrFail($id);
        $roles = Role::whereNotIn('name', ['Super Admin', 'Client'])->get();
        return view('personnel.edit-personnel', compact('user', 'roles'));
    }
    /**
     * update Personnel (Staff,Workers,Contractors).
     **
     */
    public function updatePersonnel(UpdatePersonnelRequest $request, $id)
    {
        $user = User::findOrFail($id);

        // Check if the authenticated user has permission to update this personnel
        $this->authorize('update', User::class);

        DB::transaction(function () use ($request, $user) {
            $validateData = $request->all();

            $user->update([
                'name' => $validateData['first_name'] . ' ' . $validateData['last_name'],
                'email' => $validateData['email'],
                'phone_number' => $validateData['phone_number'],
                'status' => $validateData['status'],
                'user_type' => $validateData['user_type'] ?? 'staff',
            ]);

            // Assign role
            if (isset($validateData['role'])) {
                $user->syncRoles($validateData['role']);
            }

            // file uploads if they exist
            if ($request->hasFile('picture')) {
                // Remove old picture if exists
                if ($user->personnel && $user->personnel->picture) {
                    $oldPicturePath = public_path('personnel/pictures/' . $user->personnel->picture);
                    if (file_exists($oldPicturePath)) {
                        @unlink($oldPicturePath);
                    }
                }
                $file = $request->file('picture');
                $datePrefix = date('d-m-Y');
                $filename = $datePrefix . '_' . $file->getClientOriginalName();
                $file->move(public_path('personnel/pictures'), $filename);
                $validateData['picture'] =  $filename;
            }
            if ($request->hasFile('cv')) {
                // Remove old CV if exists
                if ($user->personnel && $user->personnel->cv) {
                    $oldCvPath = public_path('personnel/cv/' . $user->personnel->cv);
                    if (file_exists($oldCvPath)) {
                        @unlink($oldCvPath);
                    }
                }
                $file = $request->file('cv');
                $datePrefix = date('d-m-Y');
                $filename = $datePrefix . '_' . $file->getClientOriginalName();
                $file->move(public_path('personnel/cv'), $filename);
                $validateData['cv'] = $filename;
            }

            // Remove fields not needed for Personnel
            unset($validateData['role'], $validateData['user_type']);
            // Update or create personnel record
            
            if ($user->personnel && $user->personnel->id) {
                $user->personnel->update($validateData);
            } else {
                $validateData['business_id'] = auth()->user()->business_id;
                $validateData['user_id'] = $user->id;
                Personnel::create($validateData);
            }
        });

        return redirect()->route('personnel')->with('message', 'Personnel Updated successfully.');
    }

    /**
     * Delete Personnel (Staff,Workers,Contractors).
     **
     */
    public function deletePersonnel($id)
    {
        $user = User::findOrFail($id);

        $this->authorize('delete', User::class);

        // delete the user picture & cv from Storage
        if ($user->personnel && $user->personnel->cv) {
            $oldCvPath = public_path('/personnel/cv/' . $user->personnel->cv);
            if (file_exists($oldCvPath)) {
                @unlink($oldCvPath);
            }
        }
        if ($user->personnel && $user->personnel->picture) {
            $oldPicturePath = public_path('/personnel/pictures/' . $user->personnel->picture);
            if (file_exists($oldPicturePath)) {
                @unlink($oldPicturePath);
            }
        }
        // Delete personnel & user record
        if($user->personnel){
            $user->personnel->delete();
        }
        $user->delete();
        
        return redirect()->route('personnel')->with('message', 'Personnel Deleted successfully.');
    }


}
