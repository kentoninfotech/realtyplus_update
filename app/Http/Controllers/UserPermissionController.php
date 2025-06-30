<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Services\GroupPermissions;

class UserPermissionController extends Controller
{
    protected $groupPermissions;

    public function __construct(GroupPermissions $groupPermissions) {
        $this->groupPermissions = $groupPermissions;
        $this->middleware('auth');
    }

    /**
     * Display a listing of the users with their roles and permissions.
     *
     * @return \Illuminate\View\View
     */
    public function index() {
        $roles = Role::whereNotIn('name', ['Super Admin', 'Client'])->get();
        $users = User::with('roles', 'permissions')->where('business_id', auth()->user()->business_id)->whereNotIn('user_type', ['client', 'supplier', 'labourer'])->paginate(10);
        return view('roles.index', compact('users', 'roles'));
    }

    public function edit(User $user) {
        $roles = Role::whereNotIn('name', ['Super Admin', 'Client'])->get();
        $users = User::with('roles', 'permissions')->where('business_id', auth()->user()->business_id)->whereNotIn('user_type', ['client', 'supplier', 'labourer'])->paginate(10);
        $groupPermissions = $this->groupPermissions->permission();
        return view('roles.user-roles', compact('user', 'users', 'roles', 'groupPermissions'));
    }

    public function update(Request $request, User $user) {
        $user->syncRoles($request->roles);
        $user->syncPermissions($request->permissions);
        return redirect()->route('users.role');
    }
}