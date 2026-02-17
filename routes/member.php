<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Member\CardController;
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


    // Route::group(['middleware' => ['CheckProfile']],function () {

    Route::get('stats', [StatsController::class, 'stats'])->name('member.stats');

    Route::get('my-profile', [ProfileController::class, 'myProfile'])->name('user.profile');
    Route::put('my-profile/save', [ProfileController::class, 'myProfileSave'])->name('user.profile.save');
    Route::get('my-profile/template', [ProfileController::class, 'template'])->name('user.profile.template');
    Route::put('my-profile/template', [ProfileController::class, 'templateSave'])->name('user.profile.template.save');
    Route::post('add-address', [ProfileController::class, 'saveMyAddress'])->name('address.new');
    Route::post('get-address', [ProfileController::class, 'getAddress'])->name('address.get');
    Route::post('update-address', [ProfileController::class, 'updateMyAddress'])->name('address.update');

    Route::get('my-card', [CardController::class, 'index'])->name('user.card');
    Route::put('my-card/save', [CardController::class, 'update'])->name('user.card.save');
    Route::post('place-order', [CardController::class, 'placeOrder'])->name('order.place');

    Route::group(['prefix' => '/shop'], function () {
        Route::get('/', [ShopController::class, 'index'])->name('user.shop');
        Route::post('/store', [ShopController::class, 'store'])->name('user.shop.store');
        Route::post('/store/{slug}', [ShopController::class, 'update'])->name('user.shop.update');

        Route::group(['prefix' => '/product'], function () {
            Route::post('/store', [ShopController::class, 'storeProduct'])->name('user.product.store');
            Route::get('/edit', [ShopController::class, 'editProduct'])->name('user.product.edit');
            Route::post('/update/{slug}', [ShopController::class, 'updateProduct'])->name('user.product.update');
        });

    });
    Route::group(['prefix' => '/social'], function () {
        Route::get('/', [SocialLinkController::class, 'index'])->name('user.socials');
        Route::post('/store', [SocialLinkController::class, 'store'])->name('user.social.store');
        Route::get('/delete/{id}', [SocialLinkController::class, 'destroy'])->name('user.social.destroy');
    });

});
// });
