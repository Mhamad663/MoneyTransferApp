<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SocialAuthController extends Controller
{
    // GOOGLE REDIRECT
    public function redirectGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // GOOGLE CALLBACK
    public function callbackGoogle()
    {
        $g = Socialite::driver('google')->stateless()->user();

        $user = User::firstOrCreate(
            ['email' => $g->getEmail()],
            [
                'name' => $g->getName(),
                'password' => bcrypt(str()->random(16))
            ]
        );

        Auth::login($user);

        return redirect('/user/dashboard');
    }

    // FACEBOOK REDIRECT
    public function redirectFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    // FACEBOOK CALLBACK
    public function callbackFacebook()
    {
        $f = Socialite::driver('facebook')->stateless()->user();

        $user = User::firstOrCreate(
            ['email' => $f->getEmail()],
            [
                'name' => $f->getName(),
                'password' => bcrypt(str()->random(16))
            ]
        );

        Auth::login($user);

        return redirect('/user/dashboard');
    }
}
