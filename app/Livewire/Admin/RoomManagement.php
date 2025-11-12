<?php
namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Support\Facades\Storage;

class RoomManagement extends Component
{
    use WithFileUploads;

    public $rooms;
    public $roomTypes;
    public $showModal = false;
    public $editMode = false;
    
    // Room form fields
    public $roomId;
    public $room_number;
    public $room_type_id;
    public $price_per_night;
    public $status = 'available';
    public $description;
    public $image;
    public $existingImagePath;

    // Room Type form
    public $showTypeModal = false;
    public $type_name;
    public $capacity;
    public $amenities = [];

    protected $rules = [
        'room_number' => 'required|string|unique:rooms,room_number',
        'room_type_id' => 'required|exists:room_types,id',
        'price_per_night' => 'required|numeric|min:0',
        'status' => 'required|in:available,occupied,maintenance',
        'description' => 'nullable|string',
        'image' => 'nullable|image|max:2048',
    ];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->rooms = Room::with('roomType')->get();
        $this->roomTypes = RoomType::all();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function openEditModal($roomId)
    {
        $room = Room::findOrFail($roomId);
        
        $this->roomId = $room->id;
        $this->room_number = $room->room_number;
        $this->room_type_id = $room->room_type_id;
        $this->price_per_night = $room->price_per_night;
        $this->status = $room->status;
        $this->description = $room->description;
        $this->existingImagePath = $room->image_path;
        
        $this->editMode = true;
        $this->showModal = true;
    }

    public function saveRoom()
    {
        if ($this->editMode) {
            $this->validate(array_merge($this->rules, [
                'room_number' => 'required|string|unique:rooms,room_number,' . $this->roomId,
            ]));
        } else {
            $this->validate();
        }

        $data = [
            'room_number' => $this->room_number,
            'room_type_id' => $this->room_type_id,
            'price_per_night' => $this->price_per_night,
            'status' => $this->status,
            'description' => $this->description,
        ];

        if ($this->image) {
            if ($this->editMode && $this->existingImagePath) {
                Storage::disk('public')->delete($this->existingImagePath);
            }
            $data['image_path'] = $this->image->store('rooms', 'public');
        }

        if ($this->editMode) {
            Room::find($this->roomId)->update($data);
            session()->flash('success', 'Room updated successfully.');
        } else {
            Room::create($data);
            session()->flash('success', 'Room created successfully.');
        }

        $this->closeModal();
        $this->loadData();
    }

    public function deleteRoom($roomId)
    {
        $room = Room::find($roomId);
        
        if ($room->image_path) {
            Storage::disk('public')->delete($room->image_path);
        }
        
        $room->delete();
        session()->flash('success', 'Room deleted successfully.');
        $this->loadData();
    }

    public function saveRoomType()
    {
        $this->validate([
            'type_name' => 'required|string',
            'capacity' => 'required|integer|min:1',
        ]);

        RoomType::create([
            'type_name' => $this->type_name,
            'capacity' => $this->capacity,
            'amenities' => json_encode($this->amenities),
        ]);

        session()->flash('success', 'Room type created successfully.');
        $this->closeTypeModal();
        $this->loadData();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function closeTypeModal()
    {
        $this->showTypeModal = false;
        $this->type_name = '';
        $this->capacity = '';
        $this->amenities = [];
    }

    public function resetForm()
    {
        $this->roomId = null;
        $this->room_number = '';
        $this->room_type_id = '';
        $this->price_per_night = '';
        $this->status = 'available';
        $this->description = '';
        $this->image = null;
        $this->existingImagePath = null;
        $this->editMode = false;
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.admin.room-management');
    }
}
