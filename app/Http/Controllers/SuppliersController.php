<?php

namespace App\Http\Controllers;

use App\Models\suppliers;
use Illuminate\Http\Request;

class SuppliersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', suppliers::class);

        $suppliers = suppliers::paginate(50);
        return view('suppliers', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', suppliers::class);

        $validateData = $request->validate([
            'picture'=>'image|mimes:jpg,png,jpeg,gif,svg'
        ]);

        if(!empty($request->file('picture'))){
            // $picture = $request->file('picture')->getClientOriginalName();
            $picture = time().'.'.$request->picture->extension();
            // $path = $request->file('picture')->store('public/images');
            $request->picture->move(\public_path('images\materials'),$picture);
        }else{
            if($request->oldpicture==""){
                $picture = "";
            }else{
                $picture = $request->oldpicture;
            }
        }


        suppliers::updateOrCreate(['id'=>$request->id],[
            'supplier_name' => $request->supplier_name,
            'company_name' => $request->company_name,
            'category' => $request->category,
            'phone_number' => $request->phone_number,
            'details' => $request->details,
            'address'=>$request->address,
            'business_id'=>$request->business_id
        ]);
        $suppliers = suppliers::paginate(50);

        return view('suppliers', compact('suppliers'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\suppliers  $suppliers
     * @return \Illuminate\Http\Response
     */
    public function show(suppliers $suppliers)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\suppliers  $suppliers
     * @return \Illuminate\Http\Response
     */
    public function edit(suppliers $suppliers)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\suppliers  $suppliers
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, suppliers $suppliers)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\suppliers  $suppliers
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('delete', suppliers::class);
        
        materials::findOrFail($id)->delete();
        $message = 'The material has been deleted!';
        return redirect()->route('materials')->with(['message'=>$message]);
    }
}
