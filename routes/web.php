<?php

use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\WalletController;
use App\Http\Controllers\User\BeneficiaryController;
use App\Http\Controllers\User\TransferController;
use App\Http\Controllers\User\PaymentMethodController;
use App\Http\Controllers\User\FeePromoController;
use App\Http\Controllers\User\PaymentController;
use App\Http\Controllers\User\StripeController;
use App\Http\Controllers\User\FeesPromotionsController; 
use App\Http\Controllers\User\TransactionsController;
use App\Http\Controllers\User\TransferLiveController;
use App\Http\Controllers\User\ReviewController;
use App\Http\Controllers\User\NotificationController;
use App\Http\Controllers\User\AgentMapController;
use App\Http\Controllers\User\RefundController;



Route::get('/', fn () => view('welcome'));

Route::middleware(['auth','verified'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
   
});

Route::middleware(['auth'])->group(function () {
    //Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/user/dashboard', [DashboardController::class, 'index'])->name('user.dashboard');
    //Route::get('/agent/dashboard', [AgentDashboardController::class, 'index'])->name('agent.dashboard');
});

Route::middleware(['auth','verified'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        // Reviews
        Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
        // (optional) see your review
        Route::get('/reviews/my', [ReviewController::class, 'my'])->name('reviews.my');
    });
Route::middleware(['auth'])->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});

require __DIR__.'/auth.php';

// Social login
Route::get('/auth/google',   [SocialAuthController::class,'redirectGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [SocialAuthController::class,'callbackGoogle']);
Route::get('/auth/facebook', [SocialAuthController::class,'redirectFacebook'])->name('facebook.redirect');
Route::get('/auth/facebook/callback', [SocialAuthController::class,'callbackFacebook']);

Route::middleware(['auth','verified'])->prefix('user')->name('user.')->group(function () {
  Route::get('/wallet', [WalletController::class,'index'])->name('wallet');
  Route::post('/wallet/topup', [WalletController::class,'topup'])->name('wallet.topup');
  Route::post('/wallet/transfer', [WalletController::class,'transfer'])->name('wallet.transfer');
});



Route::middleware(['auth','verified'])->prefix('user')->name('user.')->group(function () {
  Route::get('/beneficiaries', [BeneficiaryController::class,'index'])->name('beneficiaries.index');
  Route::get('/beneficiaries/create', [BeneficiaryController::class,'create'])->name('beneficiaries.create');
  Route::post('/beneficiaries', [BeneficiaryController::class,'store'])->name('beneficiaries.store');
  Route::get('/beneficiaries/{beneficiary}/edit', [BeneficiaryController::class,'edit'])->name('beneficiaries.edit');
  Route::patch('/beneficiaries/{beneficiary}', [BeneficiaryController::class,'update'])->name('beneficiaries.update');
  Route::delete('/beneficiaries/{beneficiary}', [BeneficiaryController::class,'destroy'])->name('beneficiaries.destroy');
  Route::post('/beneficiaries/{beneficiary}/favorite', [BeneficiaryController::class,'toggleFavorite'])->name('beneficiaries.favorite');
});



Route::middleware(['auth', 'verified'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        Route::get('/send', [TransferController::class, 'create'])->name('send');
        Route::post('/send/wallet', [TransferController::class, 'storeWallet'])->name('send.wallet');
        Route::post('/send/bank', [TransferController::class, 'storeBank'])->name('send.bank');
        Route::post('/send/card', [TransferController::class, 'storeCard'])->name('send.card');
    });




Route::middleware(['auth','verified'])->prefix('user')->name('user.')->group(function () {
    Route::get('/payments', [PaymentMethodController::class,'index'])->name('payments.index');
    Route::get('/payments/create', [PaymentMethodController::class,'create'])->name('payments.create');
    Route::post('/payments/card', [PaymentMethodController::class,'storeCard'])->name('payments.card.store');
    Route::post('/payments/bank', [PaymentMethodController::class,'storeBank'])->name('payments.bank.store');
    Route::post('/payments/{paymentMethod}/default', [PaymentMethodController::class,'setDefault'])->name('payments.default');
    Route::delete('/payments/{paymentMethod}', [PaymentMethodController::class,'destroy'])->name('payments.destroy');

    // NEW: details (JSON for modal)
    Route::get('/payments/{paymentMethod}/details', [PaymentMethodController::class,'details'])
        ->name('payments.details');
});

  // Transfer details
 Route::prefix('user')->name('user.')->middleware(['auth', 'verified'])->group(function () {
    // existing user routes...
    Route::get('/transfers', [TransferController::class, 'index'])->name('transfers');
    Route::get('/transfers/{id}', [TransferController::class, 'show'])->name('transfers.show'); // ✅ add this
});



Route::middleware(['auth','verified'])
    ->prefix('user/stripe')->name('stripe.')
    ->group(function () {
        Route::get('/add-card', [StripeController::class,'showAddCard'])->name('add.card');
        Route::post('/create-setup-intent', [StripeController::class,'createSetupIntent'])->name('setup-intent');
        Route::post('/store-payment-method', [StripeController::class,'storePaymentMethod'])->name('store-pm');
    });


Route::middleware(['auth','verified'])->prefix('user')->name('user.')->group(function () {
    Route::get('/fees-promotions', [\App\Http\Controllers\User\FeesPromotionsController::class,'index'])
        ->name('fees-promotions');
});

// routes/web.php


Route::middleware(['auth', 'verified'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        // ...
        Route::get('/transactions', [TransactionsController::class, 'index'])
            ->name('transactions.index');   // <-- this is the route your dashboard expects
        Route::get('/transactions/{transfer}/receipt', [TransactionsController::class, 'receipt'])
            ->name('transactions.receipt');
        Route::get('/transactions/export/pdf', [TransactionsController::class, 'exportPdf'])
            ->name('transactions.export');
    });




Route::middleware(['auth','verified'])->prefix('user')->name('user.')->group(function () {
    Route::get('/transactions',                 [TransactionsController::class,'index'])->name('transactions');
    Route::get('/transactions/export/pdf',      [TransactionsController::class,'exportListPdf'])->name('transactions.export.pdf');
    Route::get('/transactions/{transfer}',      [TransactionsController::class,'show'])->name('transactions.show');
    Route::get('/transactions/{transfer}/pdf',  [TransactionsController::class,'pdfReceipt'])->name('transactions.pdf');
});

Route::middleware(['auth','verified'])->prefix('user')->name('user.')->group(function () {
    Route::get('/refunds', [RefundController::class,'index'])->name('refunds.index');
    Route::get('/refunds/create/{transfer}', [RefundController::class,'create'])->name('refunds.create');
    Route::post('/refunds/{transfer}', [RefundController::class,'store'])->name('refunds.store');
});



Route::middleware(['auth','verified'])->prefix('user')->name('user.')->group(function () {
    Route::get('/live-transfers', [TransferLiveController::class,'index'])->name('transfers.live');
    Route::get('/live-transfers/data', [TransferLiveController::class,'data'])->name('transfers.live.data');
});



Route::prefix('user')->name('user.')->middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
});



Route::get('/exchange-rates', [App\Http\Controllers\User\DashboardController::class, 'getRates'])
    ->name('exchange.rates');
Route::get('/user/get-rates', [DashboardController::class, 'getRates'])->name('user.getRates');





Route::middleware(['auth','verified'])->prefix('user')->name('user.')->group(function () {
    Route::get('/agents-map', [AgentMapController::class, 'index'])->name('agents.map');
    Route::get('/agents.json', [AgentMapController::class, 'json'])->name('agents.json'); // optional
});
