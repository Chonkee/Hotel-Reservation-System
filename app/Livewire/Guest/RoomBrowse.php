<?php
// app/Livewire/Guest/RoomBrowse.php
namespace App\Livewire\Guest;

use Livewire\Component;
use App\Models\RoomType;
use App\Models\Room;

class RoomBrowse extends Component
{
    public $selectedType = null;

    public function render()
    {
        $roomTypes = RoomType::with(['rooms' => function($query) {
            $query->where('status', 'available')->limit(3);
        }])->get();

        return view('livewire.guest.room-browse', [
            'roomTypes' => $roomTypes
        ]);
    }
}
