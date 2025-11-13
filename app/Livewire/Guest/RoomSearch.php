<?php
namespace App\Livewire\Guest;

use Livewire\Component;
use App\Models\Room;
use App\Models\RoomType;
use Carbon\Carbon;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Log;

class RoomSearch extends Component
{
    public $checkInDate;
    public $checkOutDate;
    public $numberOfGuests = 1;
    public $numberOfRooms = 1;
    public $searchPerformed = false;
    public $availableRooms = [];
    public $selectedRooms = [];

    public function mount()
    {
        $this->checkInDate = Carbon::today()->addDay()->format('Y-m-d');
        $this->checkOutDate = Carbon::today()->addDays(2)->format('Y-m-d');
    }

    public function searchRooms()
    {
        $this->validate([
            'checkInDate' => 'required|date|after_or_equal:today',
            'checkOutDate' => 'required|date|after:checkInDate',
            'numberOfGuests' => 'required|integer|min:1',
            'numberOfRooms' => 'required|integer|min:1|max:10',
        ]);

        try {
            $availableRooms = Room::with('roomType')
                ->where('status', 'available')
                ->get()
                ->filter(function ($room) {
                    return $room->isAvailable($this->checkInDate, $this->checkOutDate, null) 
                        && $room->roomType->capacity >= $this->numberOfGuests;
                });

            // Store as collection
            $this->availableRooms = $availableRooms->values()->all();
            $this->searchPerformed = true;
            $this->selectedRooms = [];
        } catch (\Exception $e) {
            session()->flash('error', 'Error searching rooms: ' . $e->getMessage());
            Log::error('Room search error: ' . $e->getMessage());
        }
    }

    public function toggleRoomSelection($roomId)
    {
        if (in_array($roomId, $this->selectedRooms)) {
            // Remove the room from selection
            $this->selectedRooms = array_values(array_diff($this->selectedRooms, [$roomId]));
        } else {
            // Add the room if we haven't reached the limit
            if (count($this->selectedRooms) < $this->numberOfRooms) {
                $this->selectedRooms[] = $roomId;
            }
        }
    }

    public function proceedToBooking()
    {
        if (count($this->selectedRooms) !== $this->numberOfRooms) {
            session()->flash('error', 'Please select exactly ' . $this->numberOfRooms . ' room(s)');
            return;
        }

        session([
            'booking_data' => [
                'check_in_date' => $this->checkInDate,
                'check_out_date' => $this->checkOutDate,
                'number_of_guests' => $this->numberOfGuests,
                'selected_rooms' => $this->selectedRooms,
            ]
        ]);

        return redirect()->route('reservation.create');
    }

    public function render()
{
    // Always initialize groupedRooms as empty collection
    $groupedRooms = collect([]);
    
    if ($this->searchPerformed && is_array($this->availableRooms) && count($this->availableRooms) > 0) {
        // Extract room IDs from stored data
        $roomIds = collect($this->availableRooms)->map(function ($roomData) {
            if ($roomData instanceof Room) {
                return $roomData->id;
            }
            return is_array($roomData) ? ($roomData['id'] ?? null) : null;
        })->filter()->toArray();
        
        // Fetch fresh room models from database
        if (!empty($roomIds)) {
            $rooms = Room::with('roomType')->whereIn('id', $roomIds)->get();
            $groupedRooms = $rooms->groupBy('room_type_id');
        }
    }
    
    return view('livewire.guest.room-search', [
        'groupedRooms' => $groupedRooms
    ]);
}
}