<?php
namespace App\Livewire\Guest;

use Livewire\Component;
use App\Models\Reservation;

class ReservationHistory extends Component
{
    public $filter = 'all'; // all, current, past, cancelled

    public function cancelReservation($reservationId)
    {
        $reservation = Reservation::where('id', $reservationId)
            ->where('user_id', auth()->id())
            ->first();

        if ($reservation && $reservation->status === 'pending') {
            $reservation->update(['status' => 'cancelled']);
            
            if ($reservation->payment) {
                $reservation->payment->update(['status' => 'refunded']);
            }

            session()->flash('success', 'Reservation cancelled successfully.');
        } else {
            session()->flash('error', 'Unable to cancel this reservation.');
        }
    }

    public function render()
    {
        $query = Reservation::with(['room.roomType', 'payment'])
            ->where('user_id', auth()->id())
            ->orderBy('check_in_date', 'desc');

        if ($this->filter === 'current') {
            $query->whereIn('status', ['pending', 'confirmed'])
                ->where('check_out_date', '>=', now());
        } elseif ($this->filter === 'past') {
            $query->where(function($q) {
                $q->where('status', 'completed')
                  ->orWhere(function($q2) {
                      $q2->whereIn('status', ['confirmed'])
                         ->where('check_out_date', '<', now());
                  });
            });
        } elseif ($this->filter === 'cancelled') {
            $query->where('status', 'cancelled');
        }

        $reservations = $query->get();

        return view('livewire.guest.reservation-history', [
            'reservations' => $reservations
        ]);
    }
}
