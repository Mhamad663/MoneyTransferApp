<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExchangeRate;
use Illuminate\Http\Request;
use App\Models\TransferService;
class AdminRateController extends Controller
{
    public function index()
    {
        // All transfer services the platform supports
        $services = TransferService::orderBy('code')->get();

        // later we can also pass exchange rate records
        return view('admin.rates.index', compact('services'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'from_currency' => 'required|string|size:3',
            'to_currency'   => 'required|string|size:3|different:from_currency',
            'rate'          => 'required|numeric|min:0',
            'fixed_fee'     => 'nullable|numeric|min:0',
            'percent_fee'   => 'nullable|numeric|min:0',
        ]);

        ExchangeRate::create($data);

        return back()->with('success', 'Rate created.');
    }

    public function update(Request $request, ExchangeRate $rate)
    {
        $data = $request->validate([
            'rate'        => 'required|numeric|min:0',
            'fixed_fee'   => 'nullable|numeric|min:0',
            'percent_fee' => 'nullable|numeric|min:0',
            'is_active'   => 'sometimes|boolean',
        ]);

        $data['is_active'] = $request->has('is_active');

        $rate->update($data);

        return back()->with('success', 'Rate updated.');
    }

    public function destroy(ExchangeRate $rate)
    {
        $rate->delete();

        return back()->with('success', 'Rate deleted.');
    }
}

