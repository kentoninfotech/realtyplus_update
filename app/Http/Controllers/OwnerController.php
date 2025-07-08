<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Owner;
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
        $owners = Owner::with('user', 'property')->paginate(10);
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
        //
    }


}
