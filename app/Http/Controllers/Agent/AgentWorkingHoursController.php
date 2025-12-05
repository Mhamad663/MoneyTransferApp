<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Agent;
use Illuminate\Support\Facades\Auth;

class AgentWorkingHoursController extends Controller
{
    public function index()
    {
        $agent = Agent::where('user_id', Auth::id())->firstOrFail();


        $hours = $agent->opening_hours
            ? json_decode($agent->opening_hours, true)
            : [];

        return view('agent.working-hours.index', compact('agent', 'hours'));
    }

    public function update(Request $request)
    {
        $agent = Agent::where('user_id', Auth::id())->firstOrFail();


        $agent->opening_hours = json_encode($request->hours);
        $agent->save();

        return back()->with('success', 'Working hours updated.');
    }
}
