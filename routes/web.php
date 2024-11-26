<?php

// use App\Http\Controllers\Auth\ConfirmPasswordController;
// use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
// use App\Http\Controllers\Auth\ResetPasswordController;
// use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Panel\HomeController;
use App\Http\Controllers\Panel\PDFviewController;
use Illuminate\Support\Facades\Route;

// //////////////////////////////////////////////////////////////////////////////////////////
// AUTH//////////////////////////////////////////////////////////////////////////////////////
route::prefix('panel')->group(function () {
    // Auth::routes([ 'verify' => false, ]); // verify email
    // Auth::routes([ 'reset' => false, ]); // verify email
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

    // Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    // Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    // Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
    // Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');

    // Route::get('/password/confirm', [ConfirmPasswordController::class, 'showConfirmForm'])->name('password.confirm');
    // Route::post('/password/confirm', [ConfirmPasswordController::class, 'confirm']);

    // Route::get('/email/verify', [VerificationController::class, 'show'])->name('verification.notice');
    // Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify')->middleware(['signed', 'throttle:6,1']);
    // Route::post('/email/resend', [VerificationController::class, 'resend'])->name('verification.resend');
});
// AUTH//////////////////////////////////////////////////////////////////////////////////////
// //////////////////////////////////////////////////////////////////////////////////////////

// Ana sayfa rotası
Route::get('/', function () {
    return view('welcome');
});

// //////////////////////////////////////////////////////////////////////////////////////////

route::prefix('panel')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('panel.index');

    Route::post('/pdf-upload', [PDFviewController::class, 'createPdf'])->name('pdf.upload');
    Route::get('/pdf-show/{id}', [PDFviewController::class, 'showPdf'])->name('pdf.show');
    Route::get('/pdf/view/{id}', [PDFviewController::class, 'view'])->name('pdf.view');
    Route::get('/pdf/download/{id}', [PDFviewController::class, 'downloadPdf'])->name('pdf.download');
    Route::post('/delete-pdf', [PdfviewController::class, 'deletePdf'])->name('pdf.delete');








    // CACHE CLEAR
    Route::get('/cache-clear', function () {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        Artisan::call('config:cache');
        Artisan::call('optimize:clear');
        cache()->flush();
        return '<h1>All Caches Cleared!!!</h1><br><button><a href="/">Back</a></button>';
    });
});
