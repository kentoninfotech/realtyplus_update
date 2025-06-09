<?php

namespace App\Http\Controllers;

use App\Models\accountheads;
use Illuminate\Http\Request;

class AccountheadsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', accountheads::class);

        $accountheads = accountheads::all();

        return view('account-heads', compact('accountheads'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', accountheads::class);

        accountheads::updateOrCreate(['id'=>$request->id],[
            'title' => $request->title,
            'category' => $request->category,
            'type' => $request->type,
            'description'=>$request->description,
            'business_id'=>Auth()->user()->business_id
        ]);
        $accountheads = accountheads::all();

        return view('account-heads', compact('accountheads'));

    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\accountheads  $accountheads
     * @return \Illuminate\Http\Response
     */
    public function show(accountheads $accountheads)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\accountheads  $accountheads
     * @return \Illuminate\Http\Response
     */
    public function edit(accountheads $accountheads)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\accountheads  $accountheads
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, accountheads $accountheads)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\accountheads  $accountheads
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $this->authorize('delete', accountheads::class);

      accountheads::findOrFail($id)->delete();
      $message = 'The Account Head has been deleted!';
      return redirect()->route('account-heads')->with(['message'=>$message]);
    }
}
