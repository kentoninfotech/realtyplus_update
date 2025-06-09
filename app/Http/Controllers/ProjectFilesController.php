<?php

namespace App\Http\Controllers;

use App\Models\project_files;
use App\Models\projects;
use App\Http\Requests\Storeproject_filesRequest;
use App\Http\Requests\Updateproject_filesRequest;
use Illuminate\Http\Request;


class ProjectFilesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return view('add-files');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('add-files');

    }

    public function addProjectFile($pid)
    {
        return view('add-files')->with(['project_id'=>$pid]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storeproject_filesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Check if the user is authorized to view projects
        $this->authorize('view', projects::class);

        $validateData = $request->validate([
            'picture'=>'image|mimes:jpg,png,jpeg,gif,svg,doc,docx,pdf,xls,xlsx'
        ]);

        if(!empty($request->file('file_name'))){
            // $picture = $request->file('picture')->getClientOriginalName();
            $file_name = time().'.'.$request->file_name->extension();
            // $path = $request->file('picture')->store('public/images');

            $request->file_name->move(\public_path('files/'.$request->project_id),$file_name);
        }else{
                $file_name = "";
        }

        project_files::create([
            'project_id'=>$request->project_id,
            'milestone_id'=>$request->milestone_id,
            'task_id'=>$request->task_id,
            'file_name'=>$file_name,
            'file_title'=>$request->file_title,
            'featured'=>$request->featured,
            'details'=>$request->details,
            'cloud_location'=>$request->cloud_location,
            'business_id'=>$request->business_id
        ]);
        $message = "Project File(s) uploaded successfully. <b><a href='/project-dashboard/".$request->project_id."'>Back to Project Dashboard</a></b>";
        return redirect()->back()->with(['message'=>$message]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\project_files  $project_files
     * @return \Illuminate\Http\Response
     */
    public function show(project_files $project_files)
    {
        //
    }

    public function file($fid)
    {
        $file = project_files::where('id',$fid)->first();
        return view('file')->with(['file'=>$file]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\project_files  $project_files
     * @return \Illuminate\Http\Response
     */
    public function edit(project_files $project_files)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updateproject_filesRequest  $request
     * @param  \App\Models\project_files  $project_files
     * @return \Illuminate\Http\Response
     */
    public function update(Updateproject_filesRequest $request, project_files $project_files)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\project_files  $project_files
     * @return \Illuminate\Http\Response
     */
    public function destroy(project_files $project_files)
    {
        //
    }
}
