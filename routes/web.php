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

use App\Http\Controllers\Agent\AgentAuthController;
use App\Http\Controllers\Agent\AgentDashboardController;
use App\Http\Controllers\Agent\AgentWorkingHoursController;
use App\Http\Controllers\Agent\AgentSettingsController;
use App\Http\Controllers\Agent\AgentLocationController;
use App\Http\Controllers\Agent\AgentWalletController;

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminAgentController;
use App\Http\Controllers\Admin\AdminTransactionController;
use App\Http\Controllers\Admin\AdminRateController;
use App\Http\Controllers\Admin\AdminServiceController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminRefundController;
use App\Http\Controllers\HomeController;
/*
|--------------------------------------------------------------------------
| Public marketing pages
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::view('/how-it-works', 'public.how')->name('public.how');
Route::view('/fees', 'public.fees')->name('public.fees');
Route::view('/destinations', 'public.destinations')->name('public.destinations');
Route::view('/agents', 'public.agents')->name('public.agents');
Route::view('/help', 'public.help')->name('public.help');
Route::view('/contact', 'public.contact')->name('public.contact');
Route::view('/about', 'public.about')->name('public.about');
Route::view('/terms', 'public.terms')->name('public.terms');
Route::view('/privacy', 'public.privacy')->name('public.privacy');

/*
|--------------------------------------------------------------------------
| Generic authenticated dashboard (if you still want it)
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| User dashboard
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');
    });



/*
|--------------------------------------------------------------------------
| Reviews
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
        Route::get('/reviews/my', [ReviewController::class, 'my'])->name('reviews.my');
    });

/*
|--------------------------------------------------------------------------
| Profile (FULL CRUD)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    });



require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| Social login
|--------------------------------------------------------------------------
*/

Route::get('/auth/google', [SocialAuthController::class, 'redirectGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [SocialAuthController::class, 'callbackGoogle']);
Route::get('/auth/facebook', [SocialAuthController::class, 'redirectFacebook'])->name('facebook.redirect');
Route::get('/auth/facebook/callback', [SocialAuthController::class, 'callbackFacebook']);

/*
|--------------------------------------------------------------------------
| User wallet
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        Route::get('/wallet', [WalletController::class, 'index'])->name('wallet');
        Route::post('/wallet/topup', [WalletController::class, 'topup'])->name('wallet.topup');
        Route::post('/wallet/transfer', [WalletController::class, 'transfer'])->name('wallet.transfer');
    });

/*
|--------------------------------------------------------------------------
| Beneficiaries
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        Route::get('/beneficiaries', [BeneficiaryController::class, 'index'])->name('beneficiaries.index');
        Route::get('/beneficiaries/create', [BeneficiaryController::class, 'create'])->name('beneficiaries.create');
        Route::post('/beneficiaries', [BeneficiaryController::class, 'store'])->name('beneficiaries.store');
        Route::get('/beneficiaries/{beneficiary}/edit', [BeneficiaryController::class, 'edit'])->name('beneficiaries.edit');
        Route::patch('/beneficiaries/{beneficiary}', [BeneficiaryController::class, 'update'])->name('beneficiaries.update');
        Route::delete('/beneficiaries/{beneficiary}', [BeneficiaryController::class, 'destroy'])->name('beneficiaries.destroy');
        Route::post('/beneficiaries/{beneficiary}/favorite', [BeneficiaryController::class, 'toggleFavorite'])->name('beneficiaries.favorite');
    });

/*
|--------------------------------------------------------------------------
| Sending money (transfer create)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        Route::get('/send', [TransferController::class, 'create'])->name('send');
        Route::post('/send/wallet', [TransferController::class, 'storeWallet'])->name('send.wallet');
        Route::post('/send/bank', [TransferController::class, 'storeBank'])->name('send.bank');
        Route::post('/send/card', [TransferController::class, 'storeCard'])->name('send.card');
    });

/*
|--------------------------------------------------------------------------
| Payment methods
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        Route::get('/payments', [PaymentMethodController::class, 'index'])->name('payments.index');
        Route::get('/payments/create', [PaymentMethodController::class, 'create'])->name('payments.create');
        Route::post('/payments/card', [PaymentMethodController::class, 'storeCard'])->name('payments.card.store');
        Route::post('/payments/bank', [PaymentMethodController::class, 'storeBank'])->name('payments.bank.store');
        Route::post('/payments/{paymentMethod}/default', [PaymentMethodController::class, 'setDefault'])->name('payments.default');
        Route::delete('/payments/{paymentMethod}', [PaymentMethodController::class, 'destroy'])->name('payments.destroy');
        Route::get('/payments/{paymentMethod}/details', [PaymentMethodController::class, 'details'])->name('payments.details');
    });

/*
|--------------------------------------------------------------------------
| Transfer list / details for user
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        Route::get('/transfers', [TransferController::class, 'index'])->name('transfers');
        Route::get('/transfers/{id}', [TransferController::class, 'show'])->name('transfers.show');
    });

/*
|--------------------------------------------------------------------------
| Stripe (user)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])
    ->prefix('user/stripe')
    ->name('stripe.')
    ->group(function () {
        Route::get('/add-card', [StripeController::class, 'showAddCard'])->name('add.card');
        Route::post('/create-setup-intent', [StripeController::class, 'createSetupIntent'])->name('setup-intent');
        Route::post('/store-payment-method', [StripeController::class, 'storePaymentMethod'])->name('store-pm');
    });

/*
|--------------------------------------------------------------------------
| Fees & promotions page (user)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        Route::get('/fees-promotions', [FeesPromotionsController::class, 'index'])
            ->name('fees-promotions');
    });

/*
|--------------------------------------------------------------------------
| User transactions (clean, single definition)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        Route::get('/transactions', [TransactionsController::class, 'index'])
        ->name('transactions');   // use this in dashboard: route('user.transactions.index')

        Route::get('/transactions/export/pdf', [TransactionsController::class, 'exportListPdf'])
            ->name('transactions.export.pdf');

        Route::get('/transactions/{transfer}', [TransactionsController::class, 'show'])
            ->name('transactions.show');

        Route::get('/transactions/{transfer}/pdf', [TransactionsController::class, 'pdfReceipt'])
            ->name('transactions.pdf');
    });

/*
|--------------------------------------------------------------------------
| Refund requests (user)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        Route::get('/refunds', [RefundController::class, 'index'])->name('refunds.index');
        Route::get('/refunds/create/{transfer}', [RefundController::class, 'create'])->name('refunds.create');
        Route::post('/refunds/{transfer}', [RefundController::class, 'store'])->name('refunds.store');
    });

/*
|--------------------------------------------------------------------------
| Live transfers page (user)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        Route::get('/live-transfers', [TransferLiveController::class, 'index'])->name('transfers.live');
        Route::get('/live-transfers/data', [TransferLiveController::class, 'data'])->name('transfers.live.data');
    });

/*
|--------------------------------------------------------------------------
| Notifications (user)
|--------------------------------------------------------------------------
*/

Route::prefix('user')->name('user.')->middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
});

/*
|--------------------------------------------------------------------------
| Rates endpoint used by dashboard
|--------------------------------------------------------------------------
*/

Route::get('/exchange-rates', [DashboardController::class, 'getRates'])->name('exchange.rates');
Route::get('/user/get-rates', [DashboardController::class, 'getRates'])->name('user.getRates');

/*
|--------------------------------------------------------------------------
| Agents map (user side)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        Route::get('/agents-map', [AgentMapController::class, 'index'])->name('agents.map');
        Route::get('/agents.json', [AgentMapController::class, 'json'])->name('agents.json');
    });

/*
|--------------------------------------------------------------------------
| Agent auth & dashboard
|--------------------------------------------------------------------------
*/

Route::prefix('agent')->group(function () {

    Route::middleware('guest')->group(function () {
        Route::get('/login', [AgentAuthController::class, 'showLoginForm'])->name('agent.login');
        Route::post('/login', [AgentAuthController::class, 'login']);
        Route::get('/register', [AgentAuthController::class, 'showRegisterForm'])->name('agent.register');
        Route::post('/register', [AgentAuthController::class, 'register']);
    });

    Route::middleware(['auth', 'agent'])->group(function () {
        Route::get('/dashboard', [AgentDashboardController::class, 'index'])->name('agent.dashboard');
        Route::post('/logout', [AgentAuthController::class, 'logout'])->name('agent.logout');
    });
});

/*
|--------------------------------------------------------------------------
| Agent transactions / requests / payouts
|--------------------------------------------------------------------------
*/

Route::get('/agent/transactions', [App\Http\Controllers\Agent\AgentTransactionController::class, 'index'])
    ->name('agent.transactions')
    ->middleware(['auth', 'agent']);
    Route::post('/agent/transactions/{transfer}/status', 
    [App\Http\Controllers\Agent\AgentTransactionController::class, 'updateStatus'])
    ->name('agent.transactions.status')
    ->middleware(['auth', 'agent']);

    Route::middleware(['auth', 'agent'])
    ->prefix('agent')
    ->name('agent.')
    ->group(function () {
        Route::get('/wallet-topup', [AgentWalletController::class, 'create'])
            ->name('wallet.topup.form');

        Route::post('/wallet-topup/lookup', [AgentWalletController::class, 'lookup'])
            ->name('wallet.lookup');

        Route::post('/wallet-topup', [AgentWalletController::class, 'store'])
            ->name('wallet.topup');
    });
    
Route::get('/agent/requests', [App\Http\Controllers\Agent\AgentRequestController::class, 'index'])
    ->name('agent.requests')
    ->middleware(['auth', 'agent']);

Route::post('/agent/requests/{id}/approve', [App\Http\Controllers\Agent\AgentRequestController::class, 'approve'])
    ->name('agent.requests.approve')
    ->middleware(['auth', 'agent']);

Route::post('/agent/requests/{id}/decline', [App\Http\Controllers\Agent\AgentRequestController::class, 'decline'])
    ->name('agent.requests.decline')
    ->middleware(['auth', 'agent']);

Route::get('/agent/payouts', [App\Http\Controllers\Agent\AgentPayoutController::class, 'index'])
    ->name('agent.payouts')
    ->middleware(['auth', 'agent']);

Route::post('/agent/payouts/{id}/complete', [App\Http\Controllers\Agent\AgentPayoutController::class, 'complete'])
    ->name('agent.payouts.complete')
    ->middleware(['auth', 'agent']);

/*
|--------------------------------------------------------------------------
| Agent working hours & location & settings
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'agent'])->group(function () {

    Route::get('/agent/working-hours', [AgentWorkingHoursController::class, 'index'])
        ->name('agent.workinghours');
    Route::post('/agent/working-hours', [AgentWorkingHoursController::class, 'update'])
        ->name('agent.workinghours.update');

    Route::get('/agent/location', [AgentLocationController::class, 'index'])
        ->name('agent.location');
    Route::post('/agent/location/update', [AgentLocationController::class, 'update'])
        ->name('agent.location.update');

    Route::get('/agent/settings', [AgentSettingsController::class, 'index'])->name('agent.settings');
    Route::post('/agent/settings/update', [AgentSettingsController::class, 'update'])->name('agent.settings.update');
    Route::post('/agent/settings/password', [AgentSettingsController::class, 'updatePassword'])->name('agent.settings.password');
});

/*
|--------------------------------------------------------------------------
| Admin auth (public)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login',    [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login',   [AdminAuthController::class, 'login'])->name('login.post');
    Route::get('register', [AdminAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('register',[AdminAuthController::class, 'register'])->name('register.post');
    Route::post('logout',  [AdminAuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Admin protected area
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/agents', [AdminAgentController::class, 'index'])->name('agents.index');
        Route::post('/agents/{agent}/approve', [AdminAgentController::class, 'approve'])->name('agents.approve');
        Route::post('/agents/{agent}/suspend', [AdminAgentController::class, 'suspend'])->name('agents.suspend');

        Route::get('/transactions', [AdminTransactionController::class, 'index'])
            ->name('transactions.index');
        Route::post('/transactions/{transfer}/status', [AdminTransactionController::class, 'updateStatus'])
            ->name('transactions.updateStatus');

        Route::get('/services', [AdminServiceController::class, 'index'])->name('services.index');
        Route::post('/services', [AdminServiceController::class, 'store'])->name('services.store');
        Route::put('/services/{service}', [AdminServiceController::class, 'update'])->name('services.update');
        Route::delete('/services/{service}', [AdminServiceController::class, 'destroy'])->name('services.destroy');

        Route::get('/overview', [AdminDashboardController::class, 'overview'])->name('overview.index');

        Route::get('support', function () {
            return 'Admin support / chatbot page (todo)';
        })->name('support.index');

        Route::get('/fraud-alerts', [AdminRefundController::class, 'index'])->name('fraud.index');
        Route::post('/fraud-alerts/{refund}/status', [AdminRefundController::class, 'updateStatus'])
            ->name('fraud.updateStatus');

        Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
    });
