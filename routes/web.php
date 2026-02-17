<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\PublicImport\PublicImportController;
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

// Route::group(['middleware' => ['CheckBrowser']], function () {

Route::group(['middleware' => ['auth']], function () {
    Route::get('check/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
});

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('about', [HomeController::class, 'about'])->name('about');
Route::get('contact', [HomeController::class, 'contact'])->name('contact');
Route::get('privacy', [HomeController::class, 'privacy'])->name('privacy');

Route::get('/import-world-data', [PublicImportController::class, 'index'])->name('import.world.data');
Route::post('/import-world-data', [PublicImportController::class, 'runImport'])->name('import.world.data.run');

Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/user/login', [HomeController::class, 'userLogin'])->name('login.user')->middleware('guest');

Route::post('/store/contact', [HomeController::class, 'store'])->name('contact.store');
Route::put('/update/contact/{slug}', [HomeController::class, 'profileUpdate'])->name('contact.update');

Route::get('downloadVCard/{id}', [HomeController::class, 'downloadVCard'])->name('downloadVCard');

Route::get('/{slug}/edit', [HomeController::class, 'profileEdit'])->middleware('auth')->name('editProfile');

Route::get('/auth', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    } else {
        return redirect()->route('login');
    }
})->name('check.auth');

Route::get('/dashboard', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    } else {
        return redirect()->route('login');
    }
})->name('check.dashboard');

Route::get('auth/register', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    } else {
        return redirect()->route('login');
    }
})->name('check.register');
Route::get('register', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    } else {
        return redirect()->route('login');
    }
})->name('check.register');

Route::group(["prefix" => "dashboard/ajax"], function () {
    Route::get('/get-regions', [App\Http\Controllers\Ajax\AjaxController::class, 'getRegions'])->name('get.regions');
    Route::get('/get-sub-regions', [App\Http\Controllers\Ajax\AjaxController::class, 'getSubRegions'])->name('get.sub.regions');
    Route::get('/get-countries', [App\Http\Controllers\Ajax\AjaxController::class, 'getCountries'])->name('get.countries');
    Route::get('/get-states', [App\Http\Controllers\Ajax\AjaxController::class, 'getStates'])->name('get.states');
    Route::get('/get-cities', [App\Http\Controllers\Ajax\AjaxController::class, 'getCities'])->name('get.cities');

});

Route::get('sl/{slug}', [HomeController::class, 'shortlinkOpener'])->name('shortlink');

Route::get('/{slug}', [HomeController::class, 'slug'])->name('slug');

// });

require __DIR__ . '/auth.php';
