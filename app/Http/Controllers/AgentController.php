<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agent;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CreateAgentRequest;
use App\Http\Requests\UpdateAgentRequest;

class AgentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Show all Agents.
     * Modify agents records, properties, and transactions.
     **
     */
    public function index()
    {
        $agents = Agent::with('user')->paginate(10);
        return view('personnel.agents.agents', compact('agents'));
    }

    /**
     * Show add new Agent form.
     **
     */
    public function newAgent()
    {
        return view('personnel.agents.new-agent');
    }
    /**
     * Show Agent form.
     **
     */
    public function showAgent($id)
    {
        //
    }
    /**
     * Store a new Agent.
     **
     */
    public function createAgent(CreateAgentRequest $request)
    {
        DB::transaction(function () use ($request){
            //create user
            $user = User::create([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number ?? null,
                'password' => bcrypt($request->password),
                'user_type' => 'agent',
                'status' => $request->status ?? 'active',
                'business_id' => auth()->user()->business_id,
            ]);

            // Assign agent role
            $user->assignRole('Agent');

            //set user & business ID
            $request['user_id'] = $user->id;
            $request['business_id'] = auth()->user()->business_id;

            //create agent's record linked to user
            Agent::create($request->except(['password', 'user_type', 'status']));

        });

        return redirect()->route('agents')->with('message', 'Agent created successfully.');

    }
    /**
     * Show edit Agent form.
     **
     */
    public function editAgent($id)
    {
        $agent = Agent::findOrFail($id);
        return view('personnel.agents.edit-agent', compact('agent'));
    }
    /**
     * Update Agent records.
     **
     */
    public function updateAgent(UpdateAgentRequest $request)
    {
        $agent = Agent::findOrFail($request->id);
        $user = User::findOrFail($agent->user_id);

        DB::transaction(function () use ($request, $agent, $user) {
            // Check If password is provided, hash it; otherwise, keep the existing password
            if (isset($request->password) && !empty($request->password)) {
                $request->merge(['password' => bcrypt($request->password)]);
            } else {
                $request->merge(['password' => $user->password]); // Keep the existing password if not provided 
            }

            // Update user
            $user->update([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number ?? null,
                'password' => $request->password,
                'user_type' => 'agent',
                'status' => $request->status ?? 'active',
            ]);

            // Update agent
            $agent->update($request->except(['password', 'user_type', 'status']));
        });

        return redirect()->route('agents')->with('message', 'Agent records updated successfully.');
    }
    /**
     * Delete an Agent.
     **
     */
    public function deleteAgent($id)
    {
        $agent = Agent::findOrFail($id);
        $user = User::findOrFail($agent->user_id);
        $agent->delete();
        $user->delete();
        return redirect()->route('agents')->with('message', 'Agent deleted successfully.');
    }

}
