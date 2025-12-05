<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Beneficiary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BeneficiaryController extends Controller
{
 public function index()
{
    $items = Beneficiary::where('user_id', Auth::id())
        ->latest()
        ->paginate(10);

    return view('user.beneficiaries.index', compact('items'));
}

  public function create()
  {
    return view('user.beneficiaries.create');
  }

  public function store(Request $r)
  {
    $data = $r->validate([
      'name' => 'required|string|max:120',
      'country' => 'nullable|string|max:80',
      'payout_method' => 'required|in:bank,cash,mobile_wallet,wallet',
      'bank_name' => 'nullable|string|max:120',
      'account_number' => 'nullable|string|max:120',
      'iban' => 'nullable|string|max:34',
      'swift' => 'nullable|string|max:11',
      'wallet_provider' => 'nullable|string|max:120',
      'wallet_phone' => 'nullable|string|max:40',
      'platform_wallet_id' => 'nullable|string|max:32',
      'email' => 'nullable|email|max:120',
      'phone' => 'nullable|string|max:40',
      'address' => 'nullable|string|max:255',
      'notes' => 'nullable|string|max:2000',
      'is_favorite' => 'nullable|boolean',
    ]);
    $data['user_id'] = Auth::id();
    $data['is_favorite'] = (bool)($r->is_favorite ?? false);

    Beneficiary::create($data);
    return redirect()->route('user.beneficiaries.index')->with('success','Beneficiary added.');
  }

  public function edit(Beneficiary $beneficiary)
  {
    $this->authorizeOwner($beneficiary);
    return view('user.beneficiaries.edit', ['b'=>$beneficiary]);
  }

  public function update(Request $r, Beneficiary $beneficiary)
  {
    $this->authorizeOwner($beneficiary);
    $data = $r->validate([
      'name' => 'required|string|max:120',
      'country' => 'nullable|string|max:80',
      'payout_method' => 'required|in:bank,cash,mobile_wallet,wallet',
      'bank_name' => 'nullable|string|max:120',
      'account_number' => 'nullable|string|max:120',
      'iban' => 'nullable|string|max:34',
      'swift' => 'nullable|string|max:11',
      'wallet_provider' => 'nullable|string|max:120',
      'wallet_phone' => 'nullable|string|max:40',
      'platform_wallet_id' => 'nullable|string|max:32',
      'email' => 'nullable|email|max:120',
      'phone' => 'nullable|string|max:40',
      'address' => 'nullable|string|max:255',
      'notes' => 'nullable|string|max:2000',
      'is_favorite' => 'nullable|boolean',
      'status' => 'nullable|in:active,disabled',
    ]);
    $data['is_favorite'] = (bool)($r->is_favorite ?? false);

    $beneficiary->update($data);
    return redirect()->route('user.beneficiaries.index')->with('success','Beneficiary updated.');
  }

  public function destroy(Beneficiary $beneficiary)
  {
    $this->authorizeOwner($beneficiary);
    $beneficiary->delete();
    return back()->with('success','Beneficiary deleted.');
  }

  public function toggleFavorite(Beneficiary $beneficiary)
  {
    $this->authorizeOwner($beneficiary);
    $beneficiary->update(['is_favorite' => ! $beneficiary->is_favorite]);
    return back();
  }

  private function authorizeOwner(Beneficiary $b)
  {
    abort_unless($b->user_id === Auth::id(), 403);
  }

  
}
