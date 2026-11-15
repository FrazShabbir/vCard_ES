<?php

use App\Http\Controllers\Api\V1\AdminController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CardOrderController;
use App\Http\Controllers\Api\V1\CardPublicController;
use App\Http\Controllers\Api\V1\MemberDashboardController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\PlanController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Public
    Route::get('cards/{username}', [CardPublicController::class, 'show']);
    Route::get('plans', [PlanController::class, 'index']);

    // Auth (session / Sanctum SPA)
    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::get('auth/me', [AuthController::class, 'me']);
        Route::post('auth/verify-otp', [AuthController::class, 'verifyOtp']);
        Route::post('auth/resend-otp', [AuthController::class, 'resendOtp']);
        Route::get('auth/access', [AuthController::class, 'access']);

        Route::middleware('api.verified')->group(function () {
            Route::get('member/overview', [MemberDashboardController::class, 'overview']);
            Route::get('member/stats', [MemberDashboardController::class, 'stats']);
            Route::get('member/profile', [MemberDashboardController::class, 'profileShow']);
            Route::get('member/appearance', [MemberDashboardController::class, 'appearanceShow']);
            Route::get('member/payments', [PaymentController::class, 'mine']);
            Route::get('member/card-order', [CardOrderController::class, 'show']);
            Route::get('member/card-order/quote', [CardOrderController::class, 'quote']);

            Route::middleware('entitled')->group(function () {
                Route::put('member/profile', [MemberDashboardController::class, 'profileUpdate']);
                Route::post('member/appearance/mode', [MemberDashboardController::class, 'appearanceMode']);
                Route::post('member/payments', [PaymentController::class, 'store']);
                Route::post('member/card-order', [CardOrderController::class, 'store']);
            });
        });

        Route::middleware(['api.verified', 'role:SuperAdmin'])->prefix('admin')->group(function () {
            Route::get('overview', [AdminController::class, 'overview']);
            Route::get('users', [AdminController::class, 'users']);
            Route::get('payments', [AdminController::class, 'payments']);
            Route::post('payments/{id}/confirm', [AdminController::class, 'confirmPayment']);
            Route::get('orders', [AdminController::class, 'orders']);
            Route::post('orders/{id}/status', [AdminController::class, 'updateOrderStatus']);
            Route::get('plans', [AdminController::class, 'plans']);
            Route::put('plans/{id}', [AdminController::class, 'updatePlan']);
            Route::get('exports/users.csv', [AdminController::class, 'exportUsersCsv']);
        });
    });
});
