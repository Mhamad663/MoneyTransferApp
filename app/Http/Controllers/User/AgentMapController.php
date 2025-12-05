<?php

// app/Http/Controllers/User/AgentMapController.php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Agent;

class AgentMapController extends Controller
{
    public function index()
    {
        $agents = Agent::where('is_active', true)->get([
            'id','name','address','city','country','phone','latitude','longitude','opening_hours'
        ]);

        return view('user.agents.map', compact('agents'));
    }

    // Optional JSON endpoint if you later want lazy loading
    public function json()
    {
        return Agent::where('is_active', true)->get();
    }
}
