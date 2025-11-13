@extends('layouts.app')
@section('title', 'Profile Settings')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-white via-indigo-50 to-indigo-100 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950 transition-colors duration-500">
  <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 py-10 space-y-8">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row items-center justify-between gap-5">
      <div class="flex items-center gap-4">
        <img id="photoPreview"
             src="{{ $profile->profile_photo_path ? asset('storage/'.$profile->profile_photo_path) : 'https://ui-avatars.com/api/?background=4F46E5&color=fff&name='.urlencode($user->name) }}"
             class="w-20 h-20 rounded-2xl object-cover ring-4 ring-indigo-300 dark:ring-indigo-800 shadow-md"
             alt="Profile photo">
        <div>
          <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Profile Settings</h1>
          <p class="text-sm text-gray-600 dark:text-gray-400">Update your personal, professional, and identity details securely.</p>
        </div>
      </div>
      <a href="{{ route('user.dashboard') }}"
         class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
         ← Dashboard
      </a>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
      <div class="p-4 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-700 shadow-sm">
        ✅ {{ session('success') }}
      </div>
    @endif
    @if($errors->any())
      <div class="p-4 rounded-xl bg-rose-100 dark:bg-rose-900/30 text-rose-800 dark:text-rose-300 border border-rose-300 dark:border-rose-700 shadow-sm">
        <ul class="list-disc pl-5 space-y-1">
          @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
      </div>
    @endif

    {{-- FORM --}}
    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-10">
      @csrf

      {{-- PERSONAL INFO --}}
      <section class="rounded-3xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-slate-900 shadow-lg hover:shadow-xl transition p-6 md:p-8">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
          <span class="text-indigo-600 dark:text-indigo-400">👤</span> Personal Information
        </h2>

        {{-- Profile Photo --}}
        <div class="flex flex-col sm:flex-row items-center gap-6 mb-6">
          <div class="flex-shrink-0">
            <img id="photoPreview2"
                 class="w-24 h-24 rounded-2xl object-cover ring-2 ring-indigo-300 dark:ring-indigo-700 shadow-sm"
                 src="{{ $profile->profile_photo_path ? asset('storage/'.$profile->profile_photo_path) : 'https://ui-avatars.com/api/?background=4F46E5&color=fff&name='.urlencode($user->name) }}"
                 alt="Profile Image">
          </div>
          <div class="flex-1 w-full">
            <label class="label">Profile Photo</label>
            <input id="profilePhotoInput" type="file" name="profile_photo" accept="image/*"
                   class="input file:bg-indigo-600 file:text-white hover:file:bg-indigo-700">
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">JPG, PNG up to 2MB. Preview updates instantly.</p>
          </div>
        </div>

        {{-- Info Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <div><label class="label">Full Name *</label><input type="text" name="name" value="{{ old('name', $user->name) }}" required class="input"></div>
          <div><label class="label">Email *</label><input type="email" name="email" value="{{ old('email', $user->email) }}" required class="input"></div>
          <div><label class="label">Phone</label><input type="text" name="phone" value="{{ old('phone', $profile->phone) }}" placeholder="+961 70 123 456" class="input"></div>
          <div><label class="label">Date of Birth</label><input type="date" name="dob" value="{{ old('dob', $profile->dob) }}" class="input"></div>
          <div><label class="label">Nationality</label><input type="text" name="nationality" value="{{ old('nationality', $profile->nationality) }}" placeholder="Lebanese" class="input"></div>
          <div>
            <label class="label">Marital Status</label>
            <select name="marital_status" class="input">
              <option value="">Select</option>
              <option value="Single" @selected($profile->marital_status == 'Single')>Single</option>
              <option value="Married" @selected($profile->marital_status == 'Married')>Married</option>
              <option value="Divorced" @selected($profile->marital_status == 'Divorced')>Divorced</option>
            </select>
          </div>
          <div class="sm:col-span-2 lg:col-span-3">
            <label class="label">Address</label>
            <textarea name="address" rows="2" class="input">{{ old('address', $profile->address) }}</textarea>
          </div>
        </div>
      </section>

      {{-- EMPLOYMENT --}}
      <section class="rounded-3xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-slate-900 shadow-lg hover:shadow-xl transition p-6 md:p-8">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
          💼 Employment Information
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <div><label class="label">Company</label><input type="text" name="company_name" value="{{ old('company_name', $profile->company_name) }}" class="input"></div>
          <div><label class="label">Job Title</label><input type="text" name="job_title" value="{{ old('job_title', $profile->job_title) }}" class="input"></div>
          <div><label class="label">Monthly Salary ($)</label><input type="number" name="salary" step="0.01" min="0" value="{{ old('salary', $profile->salary) }}" class="input"></div>
          <div><label class="label">Occupation</label><input type="text" name="occupation" value="{{ old('occupation', $profile->occupation) }}" class="input"></div>
          <div>
            <label class="label">Education Level</label>
            <select name="education_level" class="input">
              <option value="">Select</option>
              <option value="High School" @selected($profile->education_level == 'High School')>High School</option>
              <option value="Bachelor" @selected($profile->education_level == 'Bachelor')>Bachelor</option>
              <option value="Master" @selected($profile->education_level == 'Master')>Master</option>
              <option value="PhD" @selected($profile->education_level == 'PhD')>PhD</option>
            </select>
          </div>
        </div>
      </section>

      {{-- DOCUMENTS --}}
      <section class="rounded-3xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-slate-900 shadow-lg hover:shadow-xl transition p-6 md:p-8">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
          📎 Verification Documents
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
          <div>
            <label class="label">Passport</label>
            <input id="passportInput" type="file" name="passport" accept="image/*"
                   class="input file:bg-indigo-600 file:text-white hover:file:bg-indigo-700">
            <img id="passportPreview" class="mt-3 hidden w-32 rounded-xl ring-1 ring-gray-300 dark:ring-gray-700 shadow-sm" />
            @if($profile->passport_path)
              <a href="{{ asset('storage/'.$profile->passport_path) }}" target="_blank"
                 class="block mt-2 text-sm text-indigo-600 dark:text-indigo-400 hover:underline">View Current Passport</a>
            @endif
          </div>

          <div>
            <label class="label">ID Card</label>
            <input id="idInput" type="file" name="id_card" accept="image/*"
                   class="input file:bg-indigo-600 file:text-white hover:file:bg-indigo-700">
            <img id="idPreview" class="mt-3 hidden w-32 rounded-xl ring-1 ring-gray-300 dark:ring-gray-700 shadow-sm" />
            @if($profile->id_card_path)
              <a href="{{ asset('storage/'.$profile->id_card_path) }}" target="_blank"
                 class="block mt-2 text-sm text-indigo-600 dark:text-indigo-400 hover:underline">View Current ID</a>
            @endif
          </div>
        </div>
      </section>

      {{-- ACTION BAR --}}
      <div class="sticky bottom-4 z-50">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md shadow-lg p-4">
          <p class="text-xs text-gray-600 dark:text-gray-400">Review details before saving changes.</p>
          <div class="flex gap-3 w-full sm:w-auto">
            <a href="{{ route('user.dashboard') }}" class="flex-1 sm:flex-none text-center rounded-xl border border-gray-300 dark:border-gray-700 px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Cancel</a>
            <button type="submit" class="flex-1 sm:flex-none rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 px-6 py-2.5 text-sm font-semibold text-white shadow-md transition">Save Changes</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

{{-- Tailwind helpers --}}
<style>
  .label { @apply block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1; }
  .input {
    @apply w-full rounded-xl border border-gray-300 dark:border-gray-700
           bg-white/90 dark:bg-slate-900/60 text-gray-900 dark:text-gray-100
           px-3 py-2.5 shadow-sm outline-none transition duration-200
           focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500;
  }
</style>

{{-- JS for Live Preview --}}
<script>
  const previewImage = (inputId, previewId) => {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    input?.addEventListener('change', () => {
      const file = input.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = e => {
          preview.src = e.target.result;
          preview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
      }
    });
  };
  previewImage('profilePhotoInput', 'photoPreview');
  previewImage('passportInput', 'passportPreview');
  previewImage('idInput', 'idPreview');
</script>
@endsection
