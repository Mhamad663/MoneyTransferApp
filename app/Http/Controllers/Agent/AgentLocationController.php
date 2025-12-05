<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentLocationController extends Controller
{
    public function index()
    {
        // Fetch agent record matching logged user
        $agent = Agent::where('user_id', Auth::id())->firstOrFail();

        return view('agent.location.index', compact('agent'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $agent = Agent::where('user_id', Auth::id())->firstOrFail();

        $agent->latitude = $request->latitude;
        $agent->longitude = $request->longitude;
        $agent->save();

        return back()->with('success', 'Location updated successfully!');
    }
}
