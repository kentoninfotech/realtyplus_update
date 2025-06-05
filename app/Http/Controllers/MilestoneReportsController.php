<?php

namespace App\Http\Controllers;

use App\Models\milestone_reports;
use App\Http\Requests\Storemilestone_reportsRequest;
use App\Http\Requests\Updatemilestone_reportsRequest;
use Illuminate\Http\Request;

class MilestoneReportsController extends Controller
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
    public function create($tid)
    {
        return view('milestone-report')->with(['cid'=>$cid]);

    }

    public function newtaskReport($tid)
    {
        return view('project-taskreport')->with(['task_id'=>$tid]);

    }

    public function milestonetaskReport($trid)
    {
        $taskreport = milestone_reports::where('id',$trid)->first();
        return view('task-report')->with(['task'=>$taskreport]);

    }

    public function change_task_report_status(Request $request)
    {
        $taskreport = milestone_reports::where('id',$request->report_id)->first();
        $taskreport->approval=$request->change_approval;
        $taskreport->save();

        $message = "Report Status Changed to ".$request->change_approval;

        return redirect()->back()->with(['message'=>$message]);

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Storemilestone_reportsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Ensure milestone_id is always set if possible
        $milestone_id = $request->milestone_id;
        if (empty($milestone_id) && $request->task_id) {
            // Try to get milestone_id from the task if not provided
            $task = \App\Models\tasks::find($request->task_id);
            if ($task && $task->milestone_id) {
                $milestone_id = $task->milestone_id;
            }
        }
        milestone_reports::create([
            'milestone_id'=>$milestone_id,
            'task_id'=>$request->task_id,
            'subject'=>$request->subject,
            'start_date'=>$request->start_date,
            'end_date'=>$request->end_date,
            'recorded_by'=>$request->recorded_by,
            'category'=>$request->category,
            'approval'=>$request->approval,
            'business_id'=>$request->business_id
        ]);
        $message = "Task Report added successfully";
        return redirect()->back()->with(['message'=>$message]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\milestone_reports  $milestone_reports
     * @return \Illuminate\Http\Response
     */
    public function show(milestone_reports $milestone_reports)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\milestone_reports  $milestone_reports
     * @return \Illuminate\Http\Response
     */
    public function edit(milestone_reports $milestone_reports)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updatemilestone_reportsRequest  $request
     * @param  \App\Models\milestone_reports  $milestone_reports
     * @return \Illuminate\Http\Response
     */
    public function update(Updatemilestone_reportsRequest $request, milestone_reports $milestone_reports)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\milestone_reports  $milestone_reports
     * @return \Illuminate\Http\Response
     */
    public function destroy(milestone_reports $milestone_reports)
    {
        //
    }
}
