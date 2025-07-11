<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Owner;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CreateOwnerRequest;
use App\Http\Requests\UpdateOwnerRequest;

class OwnerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Show all Owners.
     * Modify owners records, properties, and transactions.
     **
     */
    public function index()
    {
        $owners = Owner::with('user', 'properties')->paginate(10);
        return view('personnel.owners.owners', compact('owners'));
    }

    /**
     * Show add new Owner form.
     **
     */
    public function newOwner()
    {
        return view('personnel.owners.new-owner');
    }
    /**
     * Show Owner form.
     **
     */
    public function showOwner($id)
    {
        //
    }
    /**
     * Store a new Owner.
     **
     */
    public function createOwner(CreateOwnerRequest $request)
    {
        DB::transaction(function () use ($request){
            //create user
            $user = User::create([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number ?? null,
                'password' => bcrypt($request->password),
                'user_type' => 'owner',
                'status' => $request->status ?? 'Active',
                'business_id' => auth()->user()->business_id,
            ]);

            // Assign owner role
            $user->assignRole('owner');

            //set user & business ID
            $request['user_id'] = $user->id;
            $request['business_id'] = auth()->user()->business_id;

            //create owner's record linked to user
            Owner::create($request->except(['password', 'user_type', 'status']));

        });

        return redirect()->route('owners')->with('message', 'Owner created successfully.');

    }
    /**
     * Show edit Owner form.
     **
     */
    public function editOwner($id)
    {
        $owner = Owner::findOrFail($id);
        return view('personnel.owners.edit-owner', compact('owner'));
    }
    /**
     * Update Owner records.
     **
     */
    public function updateOwner(UpdateOwnerRequest $request, $id)
    {
        $owner = Owner::findOrFail($id);
        $user = User::findOrFail($owner->user_id);

        DB::transaction(function () use ($request, $owner, $user) {
            // Check If password is provided, hash it; otherwise, keep the existing password
            if (isset($request->password) && !empty($request->password)) {
                $request->merge(['password' => bcrypt($request->password)]);
            } else {
                $request->merge(['password' => $user->password]); // Keep the existing password if not provided 
            }

            // Update user
            $user->update([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number ?? null,
                'password' => $request->password,
                'user_type' => 'owner',
                'status' => $request->status ?? 'Active',
            ]);

            // Update owner
            $owner->update($request->except(['password', 'user_type', 'status']));
        });

        return redirect()->route('owners')->with('message', 'Owner records updated successfully.');
    }
    /**
     * Delete an Owner.
     **
     */
    public function deleteOwner($id)
    {
        $owner = Owner::findOrFail($id);
        $user = User::findOrFail($owner->user_id);
        $owner->delete();
        $user->delete();
        return redirect()->route('owners')->with('message', 'Owner deleted successfully.');
    }

}
