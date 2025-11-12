<?php
namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;

class Dashboard extends Component
{
    public $stats;
    public $recentReservations;
    public $monthlyRevenue;

    public function mount()
    {
        $this->loadStatistics();
    }

    public function loadStatistics()
    {
        $this->stats = [
            'total_rooms' => Room::count(),
            'available_rooms' => Room::where('status', 'available')->count(),
            'occupied_rooms' => Room::where('status', 'occupied')->count(),
            'pending_reservations' => Reservation::where('status', 'pending')->count(),
            'confirmed_reservations' => Reservation::where('status', 'confirmed')->count(),
            'total_guests' => User::where('role', 'Guest')->count(),
            'today_checkins' => Reservation::whereDate('check_in_date', Carbon::today())
                ->whereIn('status', ['confirmed'])->count(),
            'today_checkouts' => Reservation::whereDate('check_out_date', Carbon::today())
                ->whereIn('status', ['confirmed'])->count(),
        ];

        // Monthly revenue
        $this->monthlyRevenue = Payment::where('status', 'paid')
            ->whereMonth('payment_date', Carbon::now()->month)
            ->sum('amount');

        // Recent reservations
        $this->recentReservations = Reservation::with(['user', 'room'])
            ->latest()
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}