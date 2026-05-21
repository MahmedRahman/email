<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmailFiltersController;
use App\Http\Controllers\Api\EmailFilterController as EmailFilterApiController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SwaggerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
  return session()->has('auth.user')
    ? redirect()->route('dashboard')
    : redirect()->route('login');
});

Route::middleware('guest.demo')->group(function () {
  Route::get('/login', [LoginController::class, 'show'])->name('login');
  Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
});

Route::middleware('auth.demo')->group(function () {
  Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
  Route::get('/filters', [EmailFiltersController::class, 'index'])->name('filters.index');
  Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
  Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
  Route::get('/swagger', [SwaggerController::class, 'index'])->name('swagger.index');
  Route::get('/api/openapi.json', [SwaggerController::class, 'spec'])->name('swagger.spec');
  Route::get('/api/email-filters/information', [EmailFilterApiController::class, 'information'])->name('api.email-filters.information');
  Route::get('/api/email-filters/{email_id}', [EmailFilterApiController::class, 'show'])->name('api.email-filters.show');
  Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
