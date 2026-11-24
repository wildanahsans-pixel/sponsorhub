<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SponsorHubController;

Route::get('/', [AuthController::class, 'landing'])->name('home');
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'show'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1')->name('register.post');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {


Route::middleware('role:Perusahaan')->group(function () {
    Route::get('/my-payments', [SponsorHubController::class, 'companyPayments'])
    ->name('company.payments.index');

Route::post('/my-payments/{payment}/proof', [SponsorHubController::class, 'uploadPaymentProof'])
    ->name('company.payments.proof');
    Route::get('/available-events', [SponsorHubController::class, 'companyEvents'])
        ->name('company.events.index');

    Route::get('/available-events/{event}', [SponsorHubController::class, 'showCompanyEvent'])
        ->name('company.events.show');

    Route::get('/available-events/{event}/proposal', [SponsorHubController::class, 'downloadProposal'])
        ->name('company.events.proposal');
});
Route::middleware('role:Panitia')->group(function () {
    Route::get('/customization-requests', [SponsorHubController::class, 'customizationRequests'])
        ->name('customizations.requests');

    Route::get('/customization-requests/{customization}', [SponsorHubController::class, 'showCustomizationRequest'])
        ->name('customizations.show');

    Route::post('/customization-requests/{customization}/decision', [SponsorHubController::class, 'decideCustomization'])
        ->name('customizations.decision');
});
    Route::middleware('role:Panitia')->group(function () {
        Route::get('/events', [SponsorHubController::class, 'events'])
            ->name('events.index');

        Route::get('/events/create', [SponsorHubController::class, 'createEvent'])
            ->name('events.create');

        Route::post('/events', [SponsorHubController::class, 'storeEvent'])
            ->name('events.store');

        Route::get('/events/{event}/edit', [SponsorHubController::class, 'editEvent'])
            ->name('events.edit');

        Route::put('/events/{event}', [SponsorHubController::class, 'updateEvent'])
            ->name('events.update');

        Route::delete('/events/{event}', [SponsorHubController::class, 'destroyEvent'])
            ->name('events.destroy');

        Route::get('/events/{event}/proposal', [SponsorHubController::class, 'downloadProposal'])
            ->name('events.proposal');
    });
    Route::get('/dashboard', [SponsorHubController::class, 'dashboard'])->name('dashboard');
    Route::get('/health-score', [SponsorHubController::class, 'healthScore'])->middleware('role:Panitia,Koordinator')->name('health.index');
    Route::get('/sponsors', [SponsorHubController::class, 'sponsors'])->middleware('role:Panitia,Koordinator')->name('sponsors.index');
    Route::post('/sponsors', [SponsorHubController::class, 'storeSponsor'])->middleware('role:Panitia')->name('sponsors.store');
    Route::put('/sponsors/{sponsor}', [SponsorHubController::class, 'updateSponsor'])->middleware('role:Panitia')->name('sponsors.update');
    Route::post('/sponsors/{sponsor}/follow-ups', [SponsorHubController::class, 'storeFollowUp'])->middleware('role:Panitia')->name('followups.store');
    Route::get('/benefit-evidence', [SponsorHubController::class, 'evidence'])->middleware('role:Panitia')->name('evidence.index');
    Route::post('/benefit-evidence', [SponsorHubController::class, 'uploadEvidence'])->middleware('role:Panitia')->name('evidence.store');
    Route::get('/customization', [SponsorHubController::class, 'customization'])->middleware('role:Perusahaan')->name('customization.index');
    Route::post('/customization', [SponsorHubController::class, 'saveCustomization'])->middleware('role:Perusahaan')->name('customization.store');
    Route::get('/verification', [SponsorHubController::class, 'verification'])->middleware('role:Perusahaan')->name('verification.index');
    Route::post('/verification/{evidence}', [SponsorHubController::class, 'verifyEvidence'])->middleware('role:Perusahaan')->name('verification.store');
    Route::get('/reports/dashboard', [SponsorHubController::class, 'dashboard'])->middleware('role:Koordinator')->name('reports.dashboard');
    Route::get('/reports/export', [SponsorHubController::class, 'exportReport'])->middleware('role:Koordinator')->name('reports.export');
    Route::get('/agreements', [SponsorHubController::class, 'agreements'])->middleware('role:Bendahara')->name('agreements.index');
    Route::post('/agreements', [SponsorHubController::class, 'storeAgreement'])->middleware('role:Bendahara')->name('agreements.store');
    Route::get('/payments', [SponsorHubController::class, 'payments'])->middleware('role:Bendahara')->name('payments.index');
    Route::post('/payments/{payment}/verify', [SponsorHubController::class, 'verifyPayment'])->middleware('role:Bendahara')->name('payments.verify');
});
