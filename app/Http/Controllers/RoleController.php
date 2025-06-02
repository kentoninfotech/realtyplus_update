<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Services\GroupPermissions;

class RoleController extends Controller
{
    protected $groupPermissions;

    public function __construct(GroupPermissions $groupPermissions) {
        $this->groupPermissions = $groupPermissions;
        $this->middleware('auth');
    }

    // public function index() {
    //     return view('roles.index', ['roles' => Role::all()]);
    // }

    public function create() {
        $roles = Role::where('name', '!=', 'Super Admin')->where('name', '!=', 'Client')->get();
        $groupPermissions = $this->groupPermissions->permission();
        return view('roles.add-role', compact('groupPermissions', 'roles'));
    }

    public function store(Request $request) {
        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions);
        return redirect()->back();
    }

    public function edit(Role $role) {
        if($role->name === 'Super Admin'){
            return redirect()->back();
        }
        $roleData = Role::where('name', '!=', 'Super Admin')->where('name', '!=', 'Client')->get();
        $groupPermissions = $this->groupPermissions->permission();
        return view('roles.edit-role', compact('role','roleData', 'groupPermissions'));
    }

    public function update(Request $request, Role $role) {
        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions);
        return redirect()->back();
    }

    public function destroy(Role $role) {
        $role->delete();
        return back();
    }

}