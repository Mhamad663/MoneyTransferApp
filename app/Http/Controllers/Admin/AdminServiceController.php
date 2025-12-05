<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransferService;
use Illuminate\Http\Request;

class AdminServiceController extends Controller
{
    public function index()
    {
        $services = TransferService::orderBy('method')
            ->orderBy('name')
            ->get();

        return view('admin.services.index', compact('services'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'method'      => 'required|string|max:50',     // wallet, bank, cash…
            'fee_percent' => 'required|numeric|min:0',
            'fixed_fee'   => 'required|numeric|min:0',
            'speed'       => 'nullable|string|max:50',     // Instant, 1–3 days…
            'active'      => 'sometimes|boolean',
        ]);

        $data['active'] = $request->has('active');

        TransferService::create($data);

        return back()->with('success', 'Service created.');
    }

    public function update(Request $request, TransferService $service)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'method'      => 'required|string|max:50',
            'fee_percent' => 'required|numeric|min:0',
            'fixed_fee'   => 'required|numeric|min:0',
            'speed'       => 'nullable|string|max:50',
            'active'      => 'sometimes|boolean',
        ]);

        $data['active'] = $request->has('active');

        $service->update($data);

        return back()->with('success', 'Service updated.');
    }

    public function destroy(TransferService $service)
    {
        $service->delete();

        return back()->with('success', 'Service deleted.');
    }
}
