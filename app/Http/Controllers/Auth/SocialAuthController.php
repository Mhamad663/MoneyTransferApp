<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
// app/Http/Controllers/Auth/SocialAuthController.php
use Laravel\Socialite\Facades\Socialite;
use App\Models\User; use Illuminate\Support\Facades\Auth;

class SocialAuthController extends Controller {
  public function redirectGoogle(){ return Socialite::driver('google')->redirect(); }
  public function callbackGoogle(){
    $g = Socialite::driver('google')->user();
    $user = User::firstOrCreate(['email'=>$g->getEmail()], ['name'=>$g->getName(),'password'=>bcrypt(str()->random(16))]);
    Auth::login($user); return redirect('/dashboard');
  }
  public function redirectFacebook(){ return Socialite::driver('facebook')->redirect(); }
  public function callbackFacebook(){
    $f = Socialite::driver('facebook')->user();
    $user = User::firstOrCreate(['email'=>$f->getEmail()], ['name'=>$f->getName(),'password'=>bcrypt(str()->random(16))]);
    Auth::login($user); return redirect('/dashboard');
  }
}
