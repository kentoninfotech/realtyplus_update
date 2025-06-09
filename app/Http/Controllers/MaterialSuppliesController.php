<?php

namespace App\Http\Controllers;
use App\Models\material_supplies;

use App\Models\material_stock;
use Illuminate\Http\Request;


class MaterialSuppliesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', material_supplies::class);

        $supplies = material_supplies::paginate(50);
        return view('supplies', compact('supplies'));
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
        $this->authorize('create', material_supplies::class);

        material_supplies::updateOrCreate(['id'=>$request->id],[
            'material_id' => $request->material_id,
            'supplier_id' => $request->supplier_id,
            'quantity' => $request->quantity,
            'cost_per' => $request->cost_per,
            'total_amount' => $request->total_amount,
            'date_supplied' => $request->date_supplied,
            'business_id'=>$request->business_id,
            'batchno'=>$request->batchno,
            'comfirmed_by'=>$request->confirmed_by
        ]);

        // Update Stock
        $stockid = material_stock::updateOrCreate(['material_id'=>$request->material_id],[
            'material_id'=>$request->material_id,
            'dated_added'=>$request->date_supplied,
            'business_id'=>$request->business_id
        ]);

        if($request->updating=="Yes"){
            $stockid->increment('quantity',$request->quantity);
        }
        $supplies = material_supplies::paginate(50);

        return view('supplies', compact('supplies'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\material_supplies  $material_supplies
     * @return \Illuminate\Http\Response
     */
    public function show(material_supplies $material_supplies)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\material_supplies  $material_supplies
     * @return \Illuminate\Http\Response
     */
    public function edit(material_supplies $material_supplies)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\material_supplies  $material_supplies
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, material_supplies $material_supplies)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\material_supplies  $material_supplies
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('delete', material_supplies::class);

        material_supplies::findOrFail($id)->delete();
        $message = 'The Supply record has been deleted!';
        return redirect()->route('supplies')->with(['message'=>$message]);
    }
}
