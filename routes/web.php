<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SetlistController;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\JKT48Controller;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/explore', [JKT48Controller::class,'index'])->name('explore');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
// Register & Login Routes
Route::group(['middleware' => 'guest'], function(){
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// User Dashboard
Route::middleware(['auth'])->group(function () {
    Route::get('/user/dashboard', function () {
        return view('user.dashboard');
    })->name('user.dashboard');
    Route::get('/user/profile', function () {
        return view('user.profile');
    })->name('user.profile');
    Route::get('/user/settings', function () {
        return view('user.settings');
    })->name('user.settings');
    // web.php
    Route::get('/partials/members', [MemberController::class, 'getMembers'])->name('partials.members');

    Route::get('/partials/setlist', [SetlistController::class, 'getSetlist'])->name('partials.setlists');

    Route::get('/partials/theater', function () {
        return view('partials.theater');
    });
    Route::get('/user/detailmembers', [MemberController::class, 'detailmembers'])->name('user.detailmembers');

});

// Admin Dashboard
Route::middleware([AdminMiddleware::class])->group(function () {
    Route::get('/members/create-multiple', [MemberController::class, 'createMultiple'])->name('members.createMultiple');
    Route::post('/members/store-multiple', [MemberController::class, 'storeMultiple'])->name('members.storeMultiple');
    Route::get('/members/download', [MemberController::class, 'export'])->name('members.export');
    Route::get('/admin', function () {
        return view('admin.index'); 
    })->name('admin.index');
    Route::get('/admin/setlist', function () {
        return view('admin.setlist');
    })->name('admin.setlist');
    Route::get('/admin/songs/{id}', function ($id) {
        $setlist = \App\Models\Setlist::find($id);
        $setlistTitle = $setlist ? $setlist->title : 'Unknown Setlist';
        return view('admin.songs', ['setlistId' => $id, 'setlistTitle' => $setlistTitle]);
    })->name('admin.songs');

});
Route::middleware([RedirectIfAuthenticated::class])->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
});




