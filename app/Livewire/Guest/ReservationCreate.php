<?php
namespace App\Livewire\Guest;

use Livewire\Component;
use App\Models\Room;
use App\Models\Reservation;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReservationCreate extends Component
{
    public $bookingData;
    public $selectedRooms;
    public $checkInDate;
    public $checkOutDate;
    public $numberOfGuests;
    public $totalPrice = 0;
    public $numberOfNights = 0;
    public $paymentMethod = 'credit_card';
    public $customizations = [];

    public function mount()
    {
        $this->bookingData = session('booking_data');
        
        if (!$this->bookingData) {
            return redirect()->route('rooms.search');
        }

        $this->checkInDate = $this->bookingData['check_in_date'];
        $this->checkOutDate = $this->bookingData['check_out_date'];
        $this->numberOfGuests = $this->bookingData['number_of_guests'];
        
        $this->selectedRooms = Room::with('roomType')
            ->whereIn('id', $this->bookingData['selected_rooms'])
            ->get();

        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $checkIn = Carbon::parse($this->checkInDate);
        $checkOut = Carbon::parse($this->checkOutDate);
        $this->numberOfNights = $checkIn->diffInDays($checkOut);

        $this->totalPrice = $this->selectedRooms->sum(function ($room) {
            return $room->price_per_night * $this->numberOfNights;
        });
    }

    public function updateCustomization($roomId, $field, $value)
    {
        if (!isset($this->customizations[$roomId])) {
            $this->customizations[$roomId] = [];
        }
        $this->customizations[$roomId][$field] = $value;
    }

    public function createReservation()
    {
        try {
            DB::beginTransaction();

            $reservations = [];

            foreach ($this->selectedRooms as $room) {
                $roomPrice = $room->price_per_night * $this->numberOfNights;

                $reservation = Reservation::create([
                    'user_id' => auth()->id(),
                    'room_id' => $room->id,
                    'check_in_date' => $this->checkInDate,
                    'check_out_date' => $this->checkOutDate,
                    'number_of_guests' => $this->numberOfGuests,
                    'total_price' => $roomPrice,
                    'status' => 'pending',
                ]);

                Payment::create([
                    'reservation_id' => $reservation->id,
                    'amount' => $roomPrice,
                    'payment_method' => $this->paymentMethod,
                    'status' => 'pending',
                ]);

                $reservations[] = $reservation;
            }

            DB::commit();

            session()->forget('booking_data');
            session()->flash('success', 'Reservation created successfully! Waiting for admin confirmation.');

            return redirect()->route('reservations.history');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to create reservation. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.guest.reservation-create');
    }
}