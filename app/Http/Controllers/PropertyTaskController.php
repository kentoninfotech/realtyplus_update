<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PropertyTask;
use App\Models\Property;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\UpdateTaskRequest;

class PropertyTaskController extends Controller
{
    /**
     * This controller will handle
     * Show tasks,
     * create, update and delete task.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Show all task.
     */
    public function index()
    {
        //
    }
    /**
     * Display a tasks of the property and units.
     *
     */
    public function propertyTask($id)
    {
        $property = Property::findOrFail($id);
        $users = User::where('user_type', 'staff')->get();
        $taskStatus = ['pending', 'in_progress', 'completed', 'cancelled'];
        $priorities = ['low', 'medium', 'high'];
        $tasks = $property->tasks;
        // $tasks = PropertyTask::where('property_id', $property->id)->paginate(10);
        return view('properties.property-tasks', compact('tasks','property', 'users', 'taskStatus', 'priorities'));
    }
    /**
     * Store a newly created task in the database.
     */
    public function createTask(CreateTaskRequest $request)
    {
        $validated = $request->validated();

        $taskableModel = $validated['taskable_type'];
        $taskable = $taskableModel::findOrFail($validated['taskable_id']);

        $taskable->tasks()->create($validated);

        return redirect()->back()->with('success', 'Task created successfully.');
    }

    /**
     * Update the specified task in the database.
     */
    public function updateTask(UpdateTaskRequest $request, $id)
    {
        $task = PropertyTask::findOrFail($id);
        $validated = $request->validated();

        $task->update($validated);

        return redirect()->back()->with('success', 'Task updated successfully.');
    }

    /**
     * Delete the specified task.
     */
    public function deleteTask($id)
    {
        $task = PropertyTask::findOrFail($id);
        $task->delete();

        return Redirect::back()->with('success', 'Task deleted successfully.');
    }

}
