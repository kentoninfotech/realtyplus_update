<?php

namespace App\Http\Controllers;

use App\Models\materials;
use App\Http\Requests\StorematerialsRequest;
use App\Http\Requests\UpdatematerialsRequest;
use Illuminate\Http\Request;
use App\Models\material_stock;
use App\Models\material_supplies;
use App\Models\material_checkouts;

class MaterialsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', materials::class);

        $materials = materials::paginate(50);
        return view('materials', compact('materials'));
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
     * @param  \App\Http\Requests\StorematerialsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', materials::class);

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


        $material_id = materials::updateOrCreate(['id'=>$request->id],[
            'name' => $request->name,
            'type' => $request->type,
            'category' => $request->category,
            'measurement_unit' => $request->measurement_unit,
            'size' => $request->size,
            'cost_per'=>$request->cost_per,
            'picture'=>$picture,
            'business_id'=>$request->business_id

        ])->id;

        material_stock::updateOrCreate(['material_id'=>$material_id],[
            'material_id'=>$material_id,
            'quantity' => 0,
            'business_id'=>$request->business_id

        ]);

        $materials = materials::paginate(50);

        return view('materials', compact('materials'));
    }

    public function adddMaterial(Request $request){

        $this->authorize('create', materials::class);

        material_damages::updateOrCreate(['id'=>$request->id],[
            'material_id'=>$request->material_id,
            'batchno'=>$request->batchno,
            'invoiceno'=>$request->invoiceno,
            'reason'=>$request->reason,
            'quantity'=>$request->quantity,
            'dated'=>$request->dated,
            'damaged_by'=>$request->damaged_by,
            'business_id'=>$request->business_id
        ]);

        $message = "The material damage record added successfully.";
        return redirect()->route('material-damages')->with(['message'=>$message]);
    }

    public function material($material_id)
    {
        $this->authorize('view', materials::class);

        $material = materials::where('id',$material_id)->first();
        $susupplies = material_supplies::where('material_id',$material_id)->get();
        $mcheckouts = material_checkouts::where('material_id',$material_id)->get();
        $page_title = "Material Report";
        return view('material', compact('material','susupplies','mcheckouts','page_title'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\materials  $materials
     * @return \Illuminate\Http\Response
     */
    public function show(materials $materials)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\materials  $materials
     * @return \Illuminate\Http\Response
     */
    public function edit(materials $materials)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatematerialsRequest  $request
     * @param  \App\Models\materials  $materials
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatematerialsRequest $request, materials $materials)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\materials  $materials
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('delete', materials::class);

        materials::findOrFail($id)->delete();
        $message = 'The material has been deleted!';
        return redirect()->route('materials')->with(['message'=>$message]);
    }

    public function removedMaterial($id)
    {
        $this->authorize('update', materials::class);

        material_damages::findOrFail($id)->delete();
        $message = 'The damaged material has been deleted!';
        return redirect()->route('materials')->with(['message'=>$message]);
    }
}
