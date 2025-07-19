<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\MasterData\User\Index as UserIndex;
use App\Livewire\MasterData\User\Form;
use App\Livewire\MasterData\Role\Index as RoleIndex;
use App\Livewire\MasterData\Permission\Index;
use App\Http\Controllers\AuditLogController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});



Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/users', UserIndex::class)->name('users.index');
    Route::get('/users/create', Form::class)->name('users.create');
    Route::get('/users/{id}/edit', Form::class)->name('users.edit');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/permissions', Index::class)
        ->name('permissions.index');
});

Route::get('/roles', RoleIndex::class)->middleware(['auth'])->name('roles.index');
// Route::get('/logs', [AuditLogController::class, 'index'])->name('logs.index')->middleware('can:logs.view');

Route::middleware(['auth'])->group(function () {
    Route::get('/logs', [\App\Http\Controllers\AuditLogController::class, 'index'])->name('logs.index');
});

require __DIR__ . '/auth.php';
