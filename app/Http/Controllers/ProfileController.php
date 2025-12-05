<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $profile = $user->profile ?: new Profile();
        return view('profile.edit', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $profile = $user->profile ?: new Profile(['user_id' => $user->id]);
        $validated = $request->validate([
            'phone' => 'nullable|string|max:20|regex:/^\+?[0-9\s\-]+$/',
            'dob' => 'nullable|date|before:today',
            'address' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:100',
            'job_title' => 'nullable|string|max:100',
            'salary' => 'nullable|numeric|min:0|max:100000000',
            'nationality' => 'nullable|string|max:100',
            'occupation' => 'nullable|string|max:100',
            'marital_status' => 'nullable|string|max:50',
            'education_level' => 'nullable|string|max:100',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'passport' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
            'id_card' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
        ]);


        // File handling
        if ($request->hasFile('profile_photo')) {
            if ($profile->profile_photo_path) Storage::disk('public')->delete($profile->profile_photo_path);
            $validated['profile_photo_path'] = $request->file('profile_photo')->store('profiles', 'public');
        }

        if ($request->hasFile('passport')) {
            if ($profile->passport_path) Storage::disk('public')->delete($profile->passport_path);
            $validated['passport_path'] = $request->file('passport')->store('documents', 'public');
        }

        if ($request->hasFile('id_card')) {
            if ($profile->id_card_path) Storage::disk('public')->delete($profile->id_card_path);
            $validated['id_card_path'] = $request->file('id_card')->store('documents', 'public');
        }

        $profile->fill($validated);
        $profile->save();

        return redirect()
        ->route('user.dashboard')   // ← redirect to dashboard
        ->with('success', 'Profile updated successfully.');
    }
}
