<?php

namespace App\Http\Controllers;

use App\Models\projects;
use App\Models\transactions;
use App\Models\accountheads;
use App\Models\User;
use App\Models\material_checkouts;
use App\Http\Requests\StoreprojectsRequest;
use App\Http\Requests\UpdateprojectsRequest;
use Illuminate\Http\Request;
use App\Models\project_files;
use App\Models\task_workers;
use App\Models\tasks;
use App\Models\milestone_reports;
use App\Models\project_milestones;

class ProjectsController extends Controller
{

    public function __construct() 
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', projects::class);

        if (Auth()->user()->hasRole('Client')){
            $projects = projects::where('client_id', Auth()->user()->id)->get();
            return view('projects')->with(['projects' => $projects]);
        }

        $projects = projects::all();
        return view('projects')->with(['projects' => $projects]);
    }


    public function landing()
    {
        $project_files = project_files::where('featured','Yes')->get();
        return view('landing.index')->with(['projects'=>$project_files]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($cid)
    {
        $this->authorize('create', projects::class);

        return view('new-project')->with(['client_id'=>$cid]);

    }

    public function newProject()
    {
        $this->authorize('create', projects::class);

        return view('new-project');

    }

    public function clientProjects($cid)
    {
        $clientprojects = projects::where('client_id',$cid)->get();
        // Authorize on the first project if exists, or skip if none
        if ($clientprojects->count() > 0) {
            $this->authorize('view', $clientprojects->first());
        } else {
            $this->authorize('viewAny', projects::class);
        }
        return view('projects')->with(['projects'=>$clientprojects]);

    }

    public function projectDashboard($pid)
    {
        $project = projects::where('id',$pid)->first();

        $totalAmount = transactions::where('project_id', $pid)->sum('amount');

        $this->authorize('view', $project);
        
        return view('project-dashboard')->with(['project' => $project, 'totalAmount' => $totalAmount]);

    }

    public function editProject($pid)
    {
        $project = projects::where('id',$pid)->first();

        $this->authorize('update', $project);

        return view('new-project')->with(['project'=>$project]);
    }




    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreprojectsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->project_id!=''){
            $outcome = "modified";
        }else{
            $outcome = "created";
        }

        $this->authorize('create', projects::class);

        projects::updateOrCreate(['id'=>$request->project_id],[
            'client_id'=>$request->client_id,
            'title'=>$request->title,
            'location'=>$request->location,
            'start_date'=>$request->start_date,
            'estimated_duration'=>$request->estimated_duration,
            'duration'=>$request->duration,
            'details'=>$request->details,
            'project_manager'=>$request->project_manager,
            'status'=>$request->status,
            'business_id'=>Auth()->user()->business_id
        ]);

        $message = 'The project has been '.$outcome.' successfully';

        return redirect()->route('projects')->with(['message'=>$message]);
    }

    /**
     * Show all transaction related to a project.
     *
     * @return \Illuminate\Http\Response
     */
    public function projectTransactions($pid)
    {
        $transactions = transactions::where('project_id',$pid)->paginate(20);
        $accountheads = accountheads::select('title','category')->get();
        $users = User::select('id','name')->get();
        $project = projects::where('id',$pid)->first();
        return view('project-transactions', compact('transactions','accountheads','users','project'));
    }

    /**
     * Show all materials related to a project.
     *
     * @return \Illuminate\Http\Response
     */
    public function projectMaterials($pid)
    {
        $materials = material_checkouts::with(['task.project'])
             ->whereHas('task', function ($query) use ($pid) {
                $query->where('project_id', $pid);
            })->paginate(10);

        $project = projects::where('id',$pid)->first();
        
        return view('project-materials', compact('materials', 'project'));
    }

    /**
     * Show all workers related to a project.
     *
     * @return \Illuminate\Http\Response
     */
    public function projectWorkers($pid)
    {
        $workers = task_workers::with(['task.project'])
             ->whereHas('task', function ($query) use ($pid) {
                $query->where('project_id', $pid);
            })->paginate(10);

        $project = projects::select('title','location')->where('id',$pid)->first();
        
        return view('project-workers', compact('workers', 'project'));
    }

    /**
     * Show all tasks related to a project.
     *
     * @return \Illuminate\Http\Response
     */
    public function projectTasks($pid)
    {
        $tasks = tasks::where('project_id', $pid)->paginate(10);
        $project = projects::select('title','location')->where('id',$pid)->first();
        return view('project-tasks', compact('tasks', 'project'));
    }

    /**
     * Show all tasks report related to a project.
     *
     * @return \Illuminate\Http\Response
     */
    public function projectReports($pid)
    {
        $reports = milestone_reports::with(['task.project'])
             ->whereHas('task', function ($query) use ($pid) {
                $query->where('project_id', $pid);
            })->paginate(10);
        $project = projects::select('title','location')->where('id',$pid)->first();
        
        return view('project-reports', compact('reports', 'project'));
    }

    /**
     * Show all milestones related to a project.
     *
     * @return \Illuminate\Http\Response
     */
    public function projectMilestones($pid)
    {
        
        $milestones = project_milestones::where('project_id', $pid)->paginate(10);
        $project = projects::select('title','location')->where('id',$pid)->first();

        $this->authorize('view', $project);
        
        return view('project-milestones', compact('milestones', 'project'));
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\projects  $projects
     * @return \Illuminate\Http\Response
     */
    public function show(projects $projects)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\projects  $projects
     * @return \Illuminate\Http\Response
     */
    public function edit(projects $projects)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateprojectsRequest  $request
     * @param  \App\Models\projects  $projects
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateprojectsRequest $request, projects $projects)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\projects  $projects
     * @return \Illuminate\Http\Response
     */
    public function destroy(projects $projects)
    {
        //
    }
}
