<?php
namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Reservation;
use App\Models\Room;

class ReservationManagement extends Component
{
    public $filter = 'pending'; // pending, confirmed, all, cancelled
    public $selectedReservation = null;
    public $showDetailsModal = false;

    public function approveReservation($reservationId)
    {
        $reservation = Reservation::with('room')->find($reservationId);
        
        if ($reservation) {
            
            if (!$reservation->room->isAvailable(
                $reservation->check_in_date, 
                $reservation->check_out_date,
                $reservationId 
            )) {
                session()->flash('error', 'Room is no longer available for these dates.');
                return;
            }

            $reservation->update(['status' => 'confirmed']);
            
            // Update payment status
            if ($reservation->payment) {
                $reservation->payment->update([
                    'status' => 'paid',
                    'payment_date' => now(),
                ]);
            }

            session()->flash('success', 'Reservation approved successfully.');
        }
    }

    public function denyReservation($reservationId)
    {
        $reservation = Reservation::find($reservationId);
        
        if ($reservation) {
            $reservation->update(['status' => 'cancelled']);
            
            // Update payment status
            if ($reservation->payment) {
                $reservation->payment->update(['status' => 'refunded']);
            }

            session()->flash('success', 'Reservation denied.');
        }
    }

    public function completeReservation($reservationId)
    {
        $reservation = Reservation::find($reservationId);
        
        if ($reservation && $reservation->status === 'confirmed') {
            $reservation->update(['status' => 'completed']);
            session()->flash('success', 'Reservation marked as completed.');
        }
    }

    public function viewDetails($reservationId)
    {
        $this->selectedReservation = Reservation::with(['user', 'room.roomType', 'payment.extraCharges'])
            ->find($reservationId);
        $this->showDetailsModal = true;
    }

    public function closeModal()
    {
        $this->showDetailsModal = false;
        $this->selectedReservation = null;
    }

    public function render()
    {
        $query = Reservation::with(['user', 'room.roomType', 'payment'])
            ->orderBy('created_at', 'desc');

        if ($this->filter === 'pending') {
            $query->where('status', 'pending');
        } elseif ($this->filter === 'confirmed') {
            $query->where('status', 'confirmed');
        } elseif ($this->filter === 'cancelled') {
            $query->where('status', 'cancelled');
        }

        $reservations = $query->get();

        return view('livewire.admin.reservation-management', [
            'reservations' => $reservations
        ]);
    }
}
