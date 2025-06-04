<?php

namespace App\Http\Controllers;

use App\Models\project_milestones;
use App\Models\tasks;
use App\Models\projects;

use App\Http\Requests\Storeproject_milestonesRequest;
use App\Http\Requests\Updateproject_milestonesRequest;
use Illuminate\Http\Request;


class ProjectMilestonesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($pid)
    {
        $project = projects::select('id','title')->where('id',$pid)->first();
        return view('project-milestone')->with(['project'=>$project]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storeproject_milestonesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Storeproject_milestonesRequest $request)
    {
        //
    }

    /**
     * Display the specified r  esource.
     *
     * @param  \App\Models\project_milestones  $project_milestones
     * @return \Illuminate\Http\Response
     */
    public function show(project_milestones $project_milestones)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\project_milestones  $project_milestones
     * @return \Illuminate\Http\Response
     */
    public function edit(project_milestones $project_milestones)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updateproject_milestonesRequest  $request
     * @param  \App\Models\project_milestones  $project_milestones
     * @return \Illuminate\Http\Response
     */
    public function update(Updateproject_milestonesRequest $request, project_milestones $project_milestones)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\project_milestones  $project_milestones
     * @return \Illuminate\Http\Response
     */
    public function destroy(project_milestones $project_milestones)
    {
        //
    }

    public function saveMilestone(Request $request){
        // project_milestones::create($request->all());

        if($request->milestone_id!=''){
            $outcome = "modified";
        }else{
            $outcome = "created";
        }


        project_milestones::updateOrCreate(['id'=>$request->milestone_id],[
            'project_id'=>$request->project_id,
            'title'=>$request->title,
            'start_date'=>$request->start_date,
            'end_date'=>$request->end_date,
            'details'=>$request->details,
            'assigned_to'=>$request->assigned_to,
            'estimated_cost'=>$request->estimated_cost,
            // 'actual_cost'=>$request->actual_cost,
            'status'=>$request->status,
            'business_id'=>Auth()->user()->business_id
        ]);

        $message = "The project milestone has been ".$outcome." successfully.  <b><a href='/project-dashboard/".$request->project_id."'>Back to Project Dashboard</a></b>";

        return redirect()->back()->with(['message'=>$message]);
    }

    public function saveMilestoneTask(Request $request){
        $savedTask = tasks::create($request->all());
        $savedTask->category='Project Task';
        $savedTask->save();

        $message = "Milestone Task added successfully";
        return redirect()->route('milestone',$request->milestone_id)->with(['message'=>$message]);
    }

    public function milestone($mid){
        $milestone = project_milestones::where('id',$mid)->first();
        return view('milestone')->with(['milestone'=>$milestone]);
    }

    public function milestoneTask($mid){
        $project_id = project_milestones::select('project_id')->where('id',$mid)->first()->project_id;
        return view('project-task')->with(['milestone_id'=>$mid,'project_id'=>$project_id]);
    }
}
