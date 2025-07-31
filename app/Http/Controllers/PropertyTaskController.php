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
        $tasks = $property->tasks;
        // $tasks = PropertyTask::where('property_id', $property->id)->paginate(10);
        return view('properties.property-tasks', compact('tasks','property'));
    }

}
