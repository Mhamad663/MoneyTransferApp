<?php

namespace App\Http\Controllers\User;
use App\Models\PaymentMethod;
use App\Http\Controllers\Controller;
use App\Models\PaymentMethod as PM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; 

class StripeController extends Controller

{

    public function index()
{
    $uid = Auth::id();
    $cards = PaymentMethod::where('user_id',$uid)->where('type','card')->latest()->get();
    $banks = PaymentMethod::where('user_id',$uid)->where('type','bank')->latest()->get();

    return view('user.payments.index', compact('cards','banks'));
}
    /** Show the Stripe Elements form to add a card */
    public function showAddCard()
    {
        // Publishable key for the client
        $pk = config('services.stripe.key');
        return view('user.payments.add_card_stripe', compact('pk'));
    }

    /** Create a SetupIntent for the logged-in user */
    public function createSetupIntent()
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $user = User::find(Auth::id());   // always an Eloquent model

if (blank($user->stripe_customer_id)) {
    $customer = \Stripe\Customer::create([
        'email' => $user->email,
        'name'  => $user->name,
    ]);

    // update via Eloquent
    $user->update(['stripe_customer_id' => $customer->id]);
}

        $intent = \Stripe\SetupIntent::create([
            'customer' => $user->stripe_customer_id,
            // 'payment_method_types' => ['card'], // optional, defaults include 'card'
        ]);

        return response()->json(['client_secret' => $intent->client_secret]);
    }

    /** Attach the PaymentMethod to the customer and store the basic details */
    public function storePaymentMethod(Request $r)
{
    $r->validate(['payment_method' => 'required|string']);

    \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
    $user = Auth::user(); 

    // attach to customer
    \Stripe\PaymentMethod::retrieve($r->payment_method)
        ->attach(['customer' => $user->stripe_customer_id]);

    $spm = \Stripe\PaymentMethod::retrieve($r->payment_method);

    // SAVE to DB (this is what the page lists)
    PM::create([
        'user_id'   => $user->id,
        'type'      => 'card',
        'brand'     => $spm->card->brand,
        'last4'     => $spm->card->last4,
        'exp_month' => $spm->card->exp_month,
        'exp_year'  => $spm->card->exp_year,
        'token'     => $spm->id,                 // Stripe PM id
        'is_default'=> ! PM::where('user_id',$user->id)->where('type','card')->exists(),
    ]);

    return redirect()->route('user.payments.index')->with('success','Card saved via Stripe.');
}
}
