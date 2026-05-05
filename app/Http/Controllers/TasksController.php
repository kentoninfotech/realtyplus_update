<?php

namespace App\Http\Controllers;

use App\Models\tasks;
use App\Models\materials;
use App\Http\Requests\StoretasksRequest;
use App\Http\Requests\UpdatetasksRequest;
use Illuminate\Http\Request;
use App\Models\task_workers;
use App\Models\categories;
use App\Models\project_files;
use App\Models\Property;
use Illuminate\Support\Facades\Artisan;

class TasksController extends Controller
{
    /**
     * Secret-protected artisan runner. Requires ?key=<secret> matching
     * env('ARTISAN_SECRET'). Allow-lists commands to avoid arbitrary execution.
     */
    private const ARTISAN_ALLOWED = [
        'migrate',
        'migrate:fresh',
        'migrate:rollback',
        'migrate:status',
        'db:seed',
        'cache:clear',
        'config:clear',
        'config:cache',
        'route:clear',
        'route:cache',
        'view:clear',
        'view:cache',
        'storage:link',
        'optimize',
        'optimize:clear',
        'queue:restart',
    ];

    private function checkArtisanSecret(Request $request)
    {
        $expected = env('ARTISAN_SECRET', 'artisanadmin');
        $provided = (string) $request->query('key', '');
        if (! hash_equals((string) $expected, $provided)) {
            abort(404);
        }
    }

    public function Artisan1(Request $request, $command)
    {
        $this->checkArtisanSecret($request);

        if (! in_array($command, self::ARTISAN_ALLOWED, true)) {
            abort(403, 'Command not allowed.');
        }

        Artisan::call($command);

        return '<pre>' . e(Artisan::output()) . '</pre>';
    }

    public function Artisan2(Request $request, $command, $param)
    {
        $this->checkArtisanSecret($request);

        if (! in_array($command, self::ARTISAN_ALLOWED, true)) {
            abort(403, 'Command not allowed.');
        }

        // Parse param as "name=value" or treat as flag like "force"
        $options = [];
        if (str_contains($param, '=')) {
            [$k, $v] = explode('=', $param, 2);
            $options['--' . ltrim($k, '-')] = $v;
        } else {
            $options['--' . ltrim($param, '-')] = true;
        }

        Artisan::call($command, $options);

        return '<pre>' . e(Artisan::output()) . '</pre>';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', tasks::class);

        return view('tasks');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($cid)
    {
        $this->authorize('create', tasks::class);

        return view('project-task')->with(['cid'=>$cid]);

    }

    // General Task
    public function newTask()
    {
        $this->authorize('create', tasks::class);

        $categories = categories::where('business_id',Auth()->user()->business_id)->get();
        return view('new-task')->with(['categories'=>$categories]);

    }

    // Save General Task
    public function saveTask(Request $request)
    {
        $this->authorize('create', tasks::class);

        tasks::updateOrCreate(['id'=>$request->tid],[
            //'project_id'=>$request->project_id,
            // 'milestone_id'=>$request->milestone_id,
            'subject'=>$request->subject,
            'start_date'=>$request->start_date,
            'end_date'=>$request->end_date,
            'details'=>$request->details,
            'assigned_to'=>$request->assigned_to,
            'category'=>$request->category,
            'estimated_cost'=>$request->estimated_cost,
            // 'actual_cost'=>$request->actual_cost,
            // 'files'=>$request->files,

            'status'=>$request->status,
            'business_id'=>Auth()->user()->business_id

        ]);

        $message = "Task created successfully!";

        return redirect()->back()->with(['message'=>$message]);

    }

    public function viewTask($tid)
    {   
        $task = tasks::where('id',$tid)->first();

        $this->authorize('view', $task);

        $materials = materials::select('id','name','measurement_unit')->get();
        return view('task')->with(['task'=>$task,'materials'=>$materials]);

    }

    public function change_task_status(Request $request)
    {
        $task = tasks::where('id',$request->task_id)->first();

        $this->authorize('update', tasks::class);

        $task->status=$request->change_status;
        $task->save();

        $message = "Task Status Changed to ".$request->change_status;

        return redirect()->back()->with(['message'=>$message]);

    }

    public function addWorkers(Request $request)
    {
        $this->authorize('create', tasks::class);

        foreach($request->worker as $key=>$worker_id){
            task_workers::create([
                'worker_id'=>$worker_id,
                'amount_paid' => $request->amountpaid[$key],
                'work_date' => $request->work_date,
                'task_id'=>$request->task_id,
                'business_id'=>Auth()->user()->business_id
            ]);

        }

        $message = "Workers added to the task";
        return redirect()->back()->with(['message'=>$message]);
    }

    public function completetask($id)
    {
        $task = tasks::where('id',$id)->first();
        $task->status = 'Completed';
        $task->save();

        $message = 'The task has been updated!';
        // audit::create([
        //     'action'=>"Task update",
        //     'description'=>'Update',
        //     'doneby'=>Auth()->user()->id,
        //     'settings_id'=>Auth()->user()->settings_id,
        // ]);
        return redirect()->route('tasks')->with(['message'=>$message]);
    }

    public function inprogresstask($id)
    {
        $task = tasks::where('id',$id)->first();

        $this->authorize('update', tasks::class);

        $task->status = 'In Progress';
        $task->save();

        $message = 'The task has been updated!';

        return redirect()->route('tasks')->with(['message'=>$message]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoretasksRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoretasksRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\tasks  $tasks
     * @return \Illuminate\Http\Response
     */
    public function show(tasks $tasks)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\tasks  $tasks
     * @return \Illuminate\Http\Response
     */
    public function edit(tasks $tasks)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatetasksRequest  $request
     * @param  \App\Models\tasks  $tasks
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatetasksRequest $request, tasks $tasks)
    {
        //
    }

    public function landing()
    {
        $project_files = project_files::where('featured','Yes')->get();
        $properties = Property::where('status','available')->get();
        return view('landing.index')->with(['projects'=>$project_files, 'properties'=>$properties]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\tasks  $tasks
     * @return \Illuminate\Http\Response
     */
    public function destroy($task_id)
    {
        $task = tasks::find($task_id);

        $this->authorize('delete', tasks::class);

        $task->delete();

        $message = "The selected task has been deleted";

        return redirect()->back()->with(['message'=>$message]);
    }
}
