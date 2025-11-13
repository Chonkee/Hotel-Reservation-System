<?php
// app/Livewire/Guest/HomePage.php
namespace App\Livewire\Guest;

use Livewire\Component;
use App\Models\RoomType;

class HomePage extends Component
{
    public function render()
    {
        $roomTypes = RoomType::with(['rooms' => function($query) {
            $query->where('status', 'available')->limit(1);
        }])->take(4)->get();

        return view('livewire.guest.home-page', [
            'roomTypes' => $roomTypes
        ]);
    }
}