<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Member\CardController;
use App\Http\Controllers\Member\CustomizerController;
use App\Http\Controllers\Member\OnboardingController;
use App\Http\Controllers\Member\ProfileController;
use App\Http\Controllers\Member\ShopController;
use App\Http\Controllers\Member\StatsController;
use App\Http\Controllers\Member\SocialLinkController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::group(['middleware' => ['role:Member', 'auth'], 'prefix' => 'auth/member'], function () {
    Route::get('/', [DashboardController::class, 'memberDashboard'])->name('member.dashboard');
    Route::get('/download-qr-code', [DashboardController::class, 'downloadQRCode'])->name('downloadQRCode');

    Route::get('onboarding', [OnboardingController::class, 'index'])->name('user.onboarding');
    Route::post('onboarding/complete', [OnboardingController::class, 'complete'])->name('user.onboarding.complete');
    Route::post('onboarding/skip', [OnboardingController::class, 'skip'])->name('user.onboarding.skip');

    Route::get('stats', [StatsController::class, 'stats'])->name('member.stats');

    Route::get('my-profile', [ProfileController::class, 'myProfile'])->name('user.profile');
    Route::put('my-profile/save', [ProfileController::class, 'myProfileSave'])->name('user.profile.save');
    Route::get('my-profile/template', [ProfileController::class, 'template'])->name('user.profile.template');
    Route::put('my-profile/template', [ProfileController::class, 'templateSave'])->name('user.profile.template.save');
    Route::get('appearance', [ProfileController::class, 'appearance'])->name('user.appearance');
    Route::post('appearance/mode', [ProfileController::class, 'appearanceMode'])->name('user.appearance.mode');
    
    Route::group(['prefix' => '/customizer'], function () {
        Route::get('/', [CustomizerController::class, 'index'])->name('user.customizer');
        Route::get('/visual/{id?}', [CustomizerController::class, 'visual'])->name('user.customizer.visual');
        Route::get('/design-json/{id}', [CustomizerController::class, 'getDesignJson'])->name('user.customizer.design.json');
        Route::post('/design-json/{id}', [CustomizerController::class, 'saveDesignJson'])->name('user.customizer.design.save');
        Route::get('/create', [CustomizerController::class, 'create'])->name('user.customizer.create');
        Route::post('/store', [CustomizerController::class, 'store'])->name('user.customizer.store');
        Route::get('/{id}/edit', [CustomizerController::class, 'edit'])->name('user.customizer.edit');
        Route::put('/{id}/update', [CustomizerController::class, 'update'])->name('user.customizer.update');
        Route::post('/{id}/activate', [CustomizerController::class, 'activate'])->name('user.customizer.activate');
        Route::delete('/{id}/delete', [CustomizerController::class, 'destroy'])->name('user.customizer.delete');
        
        // Sections management
        Route::get('/sections/{customizationId?}', [CustomizerController::class, 'sections'])->name('user.customizer.sections');
        Route::get('/section/create/{customizationId?}', [CustomizerController::class, 'sectionCreate'])->name('user.customizer.section.create');
        Route::post('/section/store/{customizationId?}', [CustomizerController::class, 'sectionStore'])->name('user.customizer.section.store');
        Route::get('/section/{id}/edit', [CustomizerController::class, 'sectionEdit'])->name('user.customizer.section.edit');
        Route::put('/section/{id}/update', [CustomizerController::class, 'sectionUpdate'])->name('user.customizer.section.update');
        Route::delete('/section/{id}/delete', [CustomizerController::class, 'sectionDestroy'])->name('user.customizer.section.delete');
        
        // Team members
        Route::post('/section/{sectionId}/team-member', [CustomizerController::class, 'teamMemberStore'])->name('user.customizer.team.store');
        Route::delete('/team-member/{id}/delete', [CustomizerController::class, 'teamMemberDestroy'])->name('user.customizer.team.delete');
        
        // Section order
        Route::post('/sections/update-order', [CustomizerController::class, 'updateSectionOrder'])->name('user.customizer.sections.order');
    });
    
    Route::post('add-address', [ProfileController::class, 'saveMyAddress'])->name('address.new');
    Route::post('get-address', [ProfileController::class, 'getAddress'])->name('address.get');
    Route::post('update-address', [ProfileController::class, 'updateMyAddress'])->name('address.update');

    Route::get('my-card', [CardController::class, 'index'])->name('user.card')->middleware('CheckProfile');
    Route::put('my-card/save', [CardController::class, 'update'])->name('user.card.save')->middleware('CheckProfile');
    Route::post('place-order', [CardController::class, 'placeOrder'])->name('order.place')->middleware('CheckProfile');

    Route::group(['prefix' => '/shop', 'middleware' => ['CheckProfile']], function () {
        Route::get('/', [ShopController::class, 'index'])->name('user.shop');
        Route::post('/store', [ShopController::class, 'store'])->name('user.shop.store');
        Route::post('/store/{slug}', [ShopController::class, 'update'])->name('user.shop.update');

        Route::group(['prefix' => '/product'], function () {
            Route::get('/create', [ShopController::class, 'createProduct'])->name('user.product.create');
            Route::post('/store', [ShopController::class, 'storeProduct'])->name('user.product.store');
            Route::get('/{slug}/edit', [ShopController::class, 'editProduct'])->name('user.product.edit');
            Route::post('/update/{slug}', [ShopController::class, 'updateProduct'])->name('user.product.update');
        });

    });
    Route::group(['prefix' => '/social'], function () {
        Route::get('/', [SocialLinkController::class, 'index'])->name('user.socials');
        Route::post('/store', [SocialLinkController::class, 'store'])->name('user.social.store');
        Route::get('/delete/{id}', [SocialLinkController::class, 'destroy'])->name('user.social.destroy');
    });

});
