@extends('layouts.profile-full')
@section('title', 'Profile Settings')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 text-gray-100">

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-10 py-10 space-y-8">

        {{-- TOP BAR --}}
        <div class="flex flex-col lg:flex-row justify-between gap-6 lg:items-center">
            <div class="flex items-start gap-4">
                <div class="relative">
                    <img id="photoPreview"
                         src="{{ $profile->profile_photo_path
                            ? asset('storage/'.$profile->profile_photo_path)
                            : 'https://ui-avatars.com/api/?background=4F46E5&color=fff&name='.urlencode($user->name) }}"
                         class="w-20 h-20 rounded-2xl object-cover ring-4 ring-indigo-500/60 shadow-xl shadow-indigo-500/20 bg-slate-900">

                    <span class="absolute -bottom-1 -right-1 inline-flex items-center justify-center w-7 h-7 rounded-xl bg-indigo-500 text-xs font-semibold shadow-lg">
                        ✨
                    </span>
                </div>

                <div class="space-y-1">
                    <div class="inline-flex items-center gap-2">
                        <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-white">
                            Profile Settings
                        </h1>
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-500/10 text-emerald-300 border border-emerald-500/40">
                            Secure Profile
                        </span>
                    </div>

                    <p class="text-sm text-slate-400 max-w-xl">
                        Keep your personal and identity information up to date to ensure smooth transactions
                        and a trusted account reputation.
                    </p>

                    <div class="flex flex-wrap gap-2 text-[11px] text-slate-400 mt-2">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-slate-800/70 border border-slate-700/70">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            Account: <span class="font-medium text-slate-200">{{ $user->email }}</span>
                        </span>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-slate-800/70 border border-slate-700/70">
                            🔒 Data is stored securely
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex flex-col items-stretch sm:items-end gap-3">
                <a href="{{ route('user.dashboard') }}"
                   class="inline-flex items-center justify-center rounded-xl border border-slate-700 bg-slate-900/60 px-4 py-2.5 text-sm font-medium text-slate-100 hover:bg-slate-800 hover:border-slate-500 transition shadow-sm">
                    ← Back to Dashboard
                </a>

                {{-- Simple progress indicator (fake / visual only) --}}
                <div class="w-full sm:w-56 space-y-1">
                    <div class="flex justify-between text-[11px] text-slate-400">
                        <span>Profile completion</span>
                        <span class="font-semibold text-indigo-300">80%</span>
                    </div>
                    <div class="h-1.5 w-full rounded-full bg-slate-800 overflow-hidden">
                        <div class="h-full w-4/5 rounded-full bg-gradient-to-r from-indigo-500 via-violet-500 to-fuchsia-500"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ALERTS --}}
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-500/10 text-emerald-200 border border-emerald-500/40 shadow-lg shadow-emerald-500/10">
                <div class="flex items-start gap-3">
                    <span class="mt-0.5 text-lg">✅</span>
                    <div>
                        <p class="font-semibold text-sm">Changes saved</p>
                        <p class="text-xs text-emerald-100/80 mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 rounded-2xl bg-rose-500/10 text-rose-100 border border-rose-500/40 shadow-lg shadow-rose-500/10">
                <div class="flex items-start gap-3">
                    <span class="mt-0.5 text-lg">⚠️</span>
                    <div>
                        <p class="font-semibold text-sm">Please review the following</p>
                        <ul class="list-disc pl-4 text-xs mt-1 space-y-0.5">
                            @foreach($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- MAIN CONTENT --}}
        <form method="POST" action="{{ route('user.profile.update') }}" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,2.1fr)_minmax(0,1fr)] gap-6">

                {{-- LEFT COLUMN / MAIN CARDS --}}
                <div class="space-y-6">

                    {{-- CARD: PERSONAL INFO --}}
                    <section class="rounded-3xl bg-slate-900/80 border border-slate-800 shadow-2xl shadow-indigo-950/40 px-6 sm:px-8 py-7 space-y-7">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h2 class="text-lg sm:text-xl font-bold text-white flex items-center gap-2">
                                    <span class="flex items-center justify-center w-9 h-9 rounded-2xl bg-indigo-500/15 text-indigo-300 text-xl">👤</span>
                                    Personal Information
                                </h2>
                                <p class="text-xs text-slate-400 mt-1">
                                    Basic details that identify you on the platform.
                                </p>
                            </div>
                            <span class="hidden sm:inline-flex text-[11px] px-2 py-1 rounded-full bg-slate-800 text-slate-300 border border-slate-700">
                                Step 1 of 3
                            </span>
                        </div>

                        {{-- Photo upload section --}}
                        <div class="flex flex-col md:flex-row items-center md:items-start gap-6 rounded-2xl bg-slate-900/80 border border-slate-800 px-4 py-4">
                            <img id="photoPreview2"
                                 src="{{ $profile->profile_photo_path
                                    ? asset('storage/'.$profile->profile_photo_path)
                                    : 'https://ui-avatars.com/api/?background=4F46E5&color=fff&name='.urlencode($user->name) }}"
                                 class="w-24 h-24 md:w-28 md:h-28 rounded-3xl object-cover ring-2 ring-indigo-500/70 shadow-lg shadow-indigo-500/25 bg-slate-950">

                            <div class="w-full space-y-2">
                                <label class="label">Profile Photo</label>
                                <div class="flex flex-col sm:flex-row gap-3 sm:items-center">
                                    <input id="profilePhotoInput"
                                           type="file"
                                           name="profile_photo"
                                           accept="image/*"
                                           class="input file:border-0 file:mr-4 file:rounded-lg file:bg-indigo-600 file:px-4 file:py-2.5 file:text-xs file:font-semibold file:text-white hover:file:bg-indigo-500 cursor-pointer">
                                    <p class="text-[11px] text-slate-400">
                                        JPG, PNG. Max 2 MB. A clear front-facing photo is recommended.
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Fields --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5 mt-4">

                            <div>
                                <label class="label">Full Name *</label>
                                <input type="text" name="name" value="{{ $user->name }}" class="input" required>
                            </div>

                            <div>
                                <label class="label">Email *</label>
                                <input type="email" name="email" value="{{ $user->email }}" class="input" required>
                            </div>

                            <div>
                                <label class="label">Phone</label>
                                <input type="text" name="phone" value="{{ $profile->phone }}" placeholder="+961 70 123 456" class="input">
                            </div>

                            <div>
                                <label class="label">Date of Birth</label>
                                <input type="date" name="dob" value="{{ $profile->dob }}" class="input">
                            </div>

                            <div>
                                <label class="label">Nationality</label>
                                <input type="text" name="nationality" value="{{ $profile->nationality }}" placeholder="Lebanese" class="input">
                            </div>

                            <div>
                                <label class="label">Marital Status</label>
                                <select name="marital_status" class="input">
                                    <option value="">Select</option>
                                    <option value="Single" @selected($profile->marital_status == 'Single')>Single</option>
                                    <option value="Married" @selected($profile->marital_status == 'Married')>Married</option>
                                    <option value="Divorced" @selected($profile->marital_status == 'Divorced')>Divorced</option>
                                </select>
                            </div>

                            <div class="sm:col-span-2 xl:col-span-3">
                                <label class="label">Address</label>
                                <textarea name="address" rows="2" class="input" placeholder="Street, city, country">{{ $profile->address }}</textarea>
                            </div>
                        </div>
                    </section>

                    {{-- CARD: EMPLOYMENT --}}
                    <section class="rounded-3xl bg-slate-900/80 border border-slate-800 shadow-2xl shadow-indigo-950/40 px-6 sm:px-8 py-7 space-y-6">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h2 class="text-lg sm:text-xl font-bold text-white flex items-center gap-2">
                                    <span class="flex items-center justify-center w-9 h-9 rounded-2xl bg-amber-500/15 text-amber-300 text-xl">💼</span>
                                    Employment Information
                                </h2>
                                <p class="text-xs text-slate-400 mt-1">
                                    Your current work and income details help us better understand your financial profile.
                                </p>
                            </div>
                            <span class="hidden sm:inline-flex text-[11px] px-2 py-1 rounded-full bg-slate-800 text-slate-300 border border-slate-700">
                                Step 2 of 3
                            </span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                            <div>
                                <label class="label">Company</label>
                                <input type="text" name="company_name" value="{{ $profile->company_name }}" class="input" placeholder="Company name">
                            </div>

                            <div>
                                <label class="label">Job Title</label>
                                <input type="text" name="job_title" value="{{ $profile->job_title }}" class="input" placeholder="Software Engineer">
                            </div>

                            <div>
                                <label class="label">Monthly Salary ($)</label>
                                <input type="number" name="salary" value="{{ $profile->salary }}" step="0.01" class="input" placeholder="e.g. 1200">
                            </div>

                            <div>
                                <label class="label">Occupation</label>
                                <input type="text" name="occupation" value="{{ $profile->occupation }}" class="input" placeholder="Student, Employee, Freelancer...">
                            </div>

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

                    {{-- CARD: DOCUMENTS --}}
                    <section class="rounded-3xl bg-slate-900/80 border border-slate-800 shadow-2xl shadow-indigo-950/40 px-6 sm:px-8 py-7 space-y-6">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h2 class="text-lg sm:text-xl font-bold text-white flex items-center gap-2">
                                    <span class="flex items-center justify-center w-9 h-9 rounded-2xl bg-emerald-500/15 text-emerald-300 text-xl">📎</span>
                                    Verification Documents
                                </h2>
                                <p class="text-xs text-slate-400 mt-1">
                                    Upload official documents to verify your identity and comply with KYC requirements.
                                </p>
                            </div>
                            <span class="hidden sm:inline-flex text-[11px] px-2 py-1 rounded-full bg-slate-800 text-slate-300 border border-slate-700">
                                Step 3 of 3
                            </span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="label">Passport</label>
                                <input type="file" name="passport" accept="image/*,application/pdf" class="input">
                                @if($profile->passport_path)
                                    <a href="{{ asset('storage/'.$profile->passport_path) }}" target="_blank"
                                       class="block mt-2 text-xs text-indigo-300 hover:text-indigo-200 hover:underline">
                                        View current passport →
                                    </a>
                                @endif
                                <p class="text-[11px] text-slate-400 mt-1">Accepted: JPG, PNG, PDF</p>
                            </div>

                            <div>
                                <label class="label">ID Card</label>
                                <input type="file" name="id_card" accept="image/*,application/pdf" class="input">
                                @if($profile->id_card_path)
                                    <a href="{{ asset('storage/'.$profile->id_card_path) }}" target="_blank"
                                       class="block mt-2 text-xs text-indigo-300 hover:text-indigo-200 hover:underline">
                                        View current ID card →
                                    </a>
                                @endif
                                <p class="text-[11px] text-slate-400 mt-1">Front + back in one file is recommended.</p>
                            </div>
                        </div>
                    </section>
                </div>

                {{-- RIGHT COLUMN / SUMMARY & TIPS --}}
                <aside class="space-y-4">

                    <div class="rounded-3xl bg-slate-900/80 border border-slate-800 shadow-2xl shadow-indigo-950/40 px-5 py-5 space-y-4">
                        <h3 class="text-sm font-semibold text-slate-100 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-xl bg-indigo-500/20 flex items-center justify-center text-indigo-300 text-sm">ℹ️</span>
                            Quick Summary
                        </h3>

                        <div class="space-y-3 text-xs text-slate-300">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-400">Name</span>
                                <span class="font-medium text-slate-100 truncate max-w-[60%] text-right">{{ $user->name }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-400">Email</span>
                                <span class="font-medium text-slate-100 truncate max-w-[60%] text-right">{{ $user->email }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-400">Phone</span>
                                <span class="font-medium text-slate-100 truncate max-w-[60%] text-right">{{ $profile->phone ?: 'Not set' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-400">Verification</span>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px]
                                             @if($profile->passport_path && $profile->id_card_path)
                                                 bg-emerald-500/15 text-emerald-300 border border-emerald-500/40
                                             @else
                                                 bg-amber-500/15 text-amber-300 border border-amber-500/40
                                             @endif">
                                    @if($profile->passport_path && $profile->id_card_path)
                                        ● Documents uploaded
                                    @else
                                        ○ Incomplete – upload required
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-3xl bg-slate-900/80 border border-slate-800 shadow-2xl shadow-indigo-950/40 px-5 py-5 space-y-3">
                        <h3 class="text-sm font-semibold text-slate-100 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-xl bg-emerald-500/20 flex items-center justify-center text-emerald-300 text-sm">✅</span>
                            Tips for a strong profile
                        </h3>
                        <ul class="space-y-2 text-xs text-slate-300">
                            <li class="flex gap-2">
                                <span class="mt-[3px] text-emerald-300">•</span>
                                <span>Use your real full name as it appears on your documents.</span>
                            </li>
                            <li class="flex gap-2">
                                <span class="mt-[3px] text-emerald-300">•</span>
                                <span>Upload clear, readable copies of your passport and ID card.</span>
                            </li>
                            <li class="flex gap-2">
                                <span class="mt-[3px] text-emerald-300">•</span>
                                <span>Keep your phone number active and up to date for security alerts.</span>
                            </li>
                        </ul>
                    </div>
                </aside>
            </div>

            {{-- ACTION BAR --}}
            <div class="sticky bottom-4 z-20">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 rounded-2xl bg-slate-900/95 border border-slate-700/80 shadow-2xl shadow-black/40 backdrop-blur px-4 py-3">
                    <p class="text-[11px] text-slate-400 flex items-center gap-2">
                        <span class="inline-flex w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                        Review your information carefully before saving.
                    </p>

                    <div class="flex gap-3">
                        <a href="{{ route('user.dashboard') }}"
                           class="px-5 py-2.5 rounded-xl border border-slate-600 bg-slate-900 text-sm text-slate-100 hover:bg-slate-800 transition shadow-sm">
                            Cancel
                        </a>

                        <button type="submit"
                                class="px-7 py-2.5 rounded-xl bg-gradient-to-r from-indigo-500 via-violet-500 to-fuchsia-500 text-sm font-semibold text-white shadow-lg shadow-violet-500/40 hover:from-indigo-400 hover:via-violet-400 hover:to-fuchsia-400 transition">
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>

<style>
    .label {
        display: block;
        font-size: 0.72rem;           
        font-weight: 600;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: #cbd5f5;               /* slate-300 */
        margin-bottom: 0.35rem;
    }

    /* Generic input styles (leave as you already have them) */
.input {
    width: 100%;
    border-radius: 0.75rem;
    border: 1px solid rgba(148, 163, 184, 0.45);
    background: rgba(15, 23, 42, 0.92);
    color: #e5e7eb;
    padding: 0.65rem 0.95rem;
    font-size: 0.9rem;
    box-shadow:
        0 0 0 1px rgba(15, 23, 42, 0.9),
        0 18px 45px rgba(15, 23, 42, 0.85);
    outline: none;
    transition: all 0.18s ease-out;
}

/* FILE INPUT – container */
input[type="file"].input {
    cursor: pointer;
    padding: 0.35rem 0.95rem; /* a bit thinner */
    background-color: rgba(15, 23, 42, 0.96);
}

/* Modern browsers */
input[type="file"].input::file-selector-button {
    margin-right: 0.75rem;
    border: none;
    border-radius: 999px; /* pill */
    padding: 0.4rem 1.1rem;
    background: linear-gradient(135deg, #4f46e5, #a855f7);
    color: #f9fafb;
    font-size: 0.8rem;
    font-weight: 600;
    letter-spacing: 0.02em;
    cursor: pointer;
    box-shadow: 0 8px 25px rgba(79, 70, 229, 0.6);
    transition: all 0.18s ease-out;
}

input[type="file"].input::file-selector-button:hover {
    filter: brightness(1.08);
    transform: translateY(-1px);
}

/* WebKit fallback (Chrome/Edge legacy, Safari) */
input[type="file"].input::-webkit-file-upload-button {
    margin-right: 0.75rem;
    border: none;
    border-radius: 999px;
    padding: 0.4rem 1.1rem;
    background: linear-gradient(135deg, #4f46e5, #a855f7);
    color: #f9fafb;
    font-size: 0.8rem;
    font-weight: 600;
    letter-spacing: 0.02em;
    cursor: pointer;
    box-shadow: 0 8px 25px rgba(79, 70, 229, 0.6);
    transition: all 0.18s ease-out;
}

input[type="file"].input::-webkit-file-upload-button:hover {
    filter: brightness(1.08);
    transform: translateY(-1px);
}

</style>



{{-- Optional: live preview for profile photo --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const input = document.getElementById('profilePhotoInput');
        const preview1 = document.getElementById('photoPreview');
        const preview2 = document.getElementById('photoPreview2');

        if (input) {
            input.addEventListener('change', (e) => {
                const file = e.target.files && e.target.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = (ev) => {
                    if (preview1) preview1.src = ev.target.result;
                    if (preview2) preview2.src = ev.target.result;
                };
                reader.readAsDataURL(file);
            });
        }
    });
</script>
@endsection
