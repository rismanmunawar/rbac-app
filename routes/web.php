<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\MasterData\User\Index as UserIndex;
use App\Livewire\MasterData\User\Form;
use App\Livewire\MasterData\Role\Index as RoleIndex;
use App\Livewire\MasterData\Permission\Index;
use App\Http\Controllers\AuditLogController;

Route::get('/', fn() => view('welcome'))->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// ⚙️ Settings via Volt
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

// 👤 User Management
Route::middleware(['auth', 'can:user.read'])->group(function () {
    Route::get('/user', UserIndex::class)->name('users.index');
});
Route::middleware(['auth', 'can:user.create'])->get('/users/create', Form::class)->name('user.create');
Route::middleware(['auth', 'can:users.update'])->get('/users/{id}/edit', Form::class)->name('user.edit');

// 🛡️ Permission Management
Route::middleware(['auth', 'can:permissions.read'])->group(function () {
    Route::get('/permissions', Index::class)->name('permissions.index');
});

// 🧩 Role Management
Route::middleware(['auth', 'can:roles.read'])->group(function () {
    Route::get('/roles', RoleIndex::class)->name('roles.index');
});

// 🕵️‍♂️ Audit Trail
Route::middleware(['auth', 'can:logs.read'])->group(function () {
    Route::get('/logs', [AuditLogController::class, 'index'])->name('logs.index');
});

// Auth routes
require __DIR__ . '/auth.php';