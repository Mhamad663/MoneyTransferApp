<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use Illuminate\Http\Request;

class AdminAgentController extends Controller
{
    
    public function index()
    {
        // Load related user 
        $agents = Agent::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);   
    
        return view('admin.agents.index', compact('agents'));
    }
    

    /*
      Approve an agent (activate).
     */
    public function approve(Agent $agent)
    {
        $agent->is_active = 1;
        $agent->save();

        return redirect()
            ->route('admin.agents.index')
            ->with('success', 'Agent approved successfully.');
    }

    /*
     Suspend / deactivate an agent.
     */
    public function suspend(Agent $agent)
    {
        $agent->is_active = 0;
        $agent->save();

        return redirect()
            ->route('admin.agents.index')
            ->with('success', 'Agent suspended successfully.');
    }
}
