<?php

use App\Http\Controllers\Admin\CategoiesController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FileManagerController;
use App\Http\Controllers\Admin\ImageUploadController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\LogoutController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PostsController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TagsController;
use App\Http\Controllers\Admin\UsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return inertia('Home');
});


use Illuminate\Support\Facades\App;

Route::get('/locale/{locale}', function (string $locale) {
    if (!in_array($locale, ['en', 'ar'])) {
        abort(400);
    }

    App::setLocale($locale);
    session()->put('locale', $locale);
    return redirect()->back();
});


Route::prefix('admin')->as('admin.')->group(function () {

    Route::middleware('guest')->group(function () {
        Route::get('/login', [LoginController::class, 'index'])->name('login.index');
        Route::post('/login', [LoginController::class, 'login'])->name('login.authenticate');
    });

    Route::middleware('auth')->group(function () {
        Route::get('/', function () {
            return redirect(route('admin.dashboard'));
        });

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
        Route::post('/profile/update/photo', [ProfileController::class, 'updateProfilePhoto'])->name('profile.updatePhoto');
        Route::post('/profile/update/info', [ProfileController::class, 'updatePersonalInfo'])->name('profile.updatePersonalInfo');
        Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.changePassword');
        Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
        Route::resource('/users', UsersController::class);
        Route::resource('/categories', CategoiesController::class)->except('edit', 'create', 'show');
        Route::resource('/tags', TagsController::class)->except('edit', 'create', 'show');
        Route::resource('posts', PostsController::class);
        Route::resource('pages', PageController::class);
        Route::post('/images/upload', [ImageUploadController::class, 'upload'])->name('images.upload');
        Route::get('/file-manager', [FileManagerController::class, 'index'])->name('file-manager.index');
        Route::post('/file-manager/create-folder', [FileManagerController::class, 'createFolder'])->name('file-manager.createFolder');
        Route::resource('/settings', SettingsController::class)->only('index', 'store', 'update', 'destroy');
    });
});
