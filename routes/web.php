<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\MasterData\User\Index as UserIndex;
use App\Livewire\MasterData\User\Form;
use App\Livewire\MasterData\Role\Index as RoleIndex;
use App\Livewire\MasterData\IT\Index as ITIndex;
use App\Livewire\MasterData\DataNom\Index as NomIndex;
use App\Livewire\MasterData\DataRom\Index as RomIndex;

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

// 👤 IT Management
Route::middleware(['auth', 'can:datait.read'])->group(function () {
    Route::get('/it', ITIndex::class)->name('its.index');
});
Route::middleware(['auth', 'can:datait.create'])->get('/dataits/create', Form::class)->name('datait.create');
Route::middleware(['auth', 'can:dataits.update'])->get('/dataits/{id}/edit', Form::class)->name('datait.edit');

// 👤 NOM Management
Route::middleware(['auth', 'can:datanom.read'])->group(function () {
    Route::get('/nom', NomIndex::class)->name('noms.index');
});

Route::middleware(['auth', 'can:datarom.read'])->group(function () {
    Route::get('/rom', RomIndex::class)->name('roms.index');
});
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
