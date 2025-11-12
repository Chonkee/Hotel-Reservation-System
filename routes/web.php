<?php
use Illuminate\Support\Facades\Route;
use App\Livewire\Guest\RoomBrowse;
use App\Livewire\Guest\RoomSearch;
use App\Livewire\Guest\ReservationCreate;
use App\Livewire\Guest\ReservationHistory;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\RoomManagement;
use App\Livewire\Admin\ReservationManagement;

// Public routes
Route::get('/', RoomBrowse::class)->name('home');
Route::get('/rooms', RoomBrowse::class)->name('rooms.browse');

require __DIR__.'/auth.php';

// Guest routes (authenticated users)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('rooms.search');
    })->name('dashboard');
    
    Route::get('/search-rooms', RoomSearch::class)->name('rooms.search');
    Route::get('/reservation/create', ReservationCreate::class)->name('reservation.create');
    Route::get('/my-reservations', ReservationHistory::class)->name('reservations.history');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/rooms', RoomManagement::class)->name('rooms');
    Route::get('/reservations', ReservationManagement::class)->name('reservations');
});
