<?php

namespace App\Http\Controllers;

use App\Models\categories;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_unless(auth()->user()->user_type === 'business_admin', 403, 'Only business administrators can manage categories.');
        $categories = categories::paginate(50);
        return view('categories', compact('categories'));
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
        abort_unless(auth()->user()->user_type === 'business_admin', 403, 'Only business administrators can manage categories.');
        categories::updateOrCreate(['id'=>$request->id],[
            'title'=>$request->title,
            'group_name'=>$request->category_group,
            'description'=>$request->description,
            'business_id'=>Auth()->user()->business_id

        ]);

        $categories = categories::paginate(50);
        $message = 'The Category has been created!';

        return view('categories', compact('categories','message'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\categories  $categories
     * @return \Illuminate\Http\Response
     */
    public function show(categories $categories)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\categories  $categories
     * @return \Illuminate\Http\Response
     */
    public function edit(categories $categories)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\categories  $categories
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, categories $categories)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\categories  $categories
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort_unless(auth()->user()->user_type === 'business_admin', 403, 'Only business administrators can manage categories.');
        categories::findOrFail($id)->delete();
        $message = 'The Category has been deleted!';
        return redirect()->route('categories')->with(['message'=>$message]);
    }
}
