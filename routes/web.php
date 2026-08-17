<?php

use Illuminate\Support\Facades\Route;
use App\Models\Video;
use App\Models\News;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', function () {
    $videos = Video::where('is_active', true)->latest()->take(1)->get(); 
    $news = News::where('is_active', true)->latest()->get();   

    return view('welcome', compact('videos', 'news'));
});

Route::middleware(['guest'])->group(function () {
    Route::livewire('/register', 'pages::auth.register')->name('auth.register');
    Route::livewire('/login', 'pages::auth.login')->name('auth.login');
});

Route::middleware('auth')->group(function () {
    Route::livewire('/panel', 'pages::participant.dashboard')->name('participant.dashboard');
    Route::livewire('/panel/wizard', 'pages::participant.registration-wizard')->name('panel.wizard');
    Route::livewire('/panel/profil', 'pages::participant.profil')->name('panel.profil');
    Route::livewire('/panel/accommodation', 'pages::participant.accommodation-info')->name('panel.accommodation');
    Route::livewire('/panel/classes', 'pages::participant.class-selection')->name('panel.classes');
    Route::livewire('/panel/schedule', 'pages::participant.schedule')->name('panel.schedule');
    Route::livewire('/panel/gallery', 'pages::participant.gallery')->name('panel.gallery');
    Route::livewire('/panel/certificate', 'pages::participant.certificate')->name('panel.certificate');


    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});