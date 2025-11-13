<div class="min-h-screen bg-gray-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Room Management</h1>
            <div class="flex space-x-4">
                <button 
                    wire:click="$set('showTypeModal', true)"
                    class="px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition"
                >
                    Add Room Type
                </button>
                <button 
                    wire:click="openModal"
                    class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition"
                >
                    Add New Room
                </button>
            </div>
        </div>

        @if(session()->has('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- Rooms Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($rooms as $room)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <!-- Room Image -->
                    <div class="h-48 bg-gray-200">
                        @if($room->image_path)
                            <img src="{{ asset('storage/' . $room->image_path) }}" 
                                 alt="Room {{ $room->room_number }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="flex items-center justify-center h-full">
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Room Details -->
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Room {{ $room->room_number }}</h3>
                                <p class="text-sm text-gray-600">{{ $room->roomType->type_name }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                {{ $room->status === 'available' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $room->status === 'occupied' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $room->status === 'maintenance' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            ">
                                {{ ucfirst($room->status) }}
                            </span>
                        </div>

                        <p class="text-sm text-gray-600 mb-4">{{ $room->description }}</p>

                        <div class="flex items-center justify-between mb-4">
                            <span class="text-2xl font-bold text-blue-600">
                                ${{ number_format($room->price_per_night, 2) }}
                            </span>
                            <span class="text-sm text-gray-600">per night</span>
                        </div>

                        <!-- Actions -->
                        <div class="flex space-x-2">
                            <button 
                                wire:click="openEditModal({{ $room->id }})"
                                class="flex-1 px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded hover:bg-blue-700 transition"
                            >
                                Edit
                            </button>
                            <button 
                                wire:click="deleteRoom({{ $room->id }})"
                                wire:confirm="Are you sure you want to delete this room?"
                                class="flex-1 px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded hover:bg-red-700 transition"
                            >
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($rooms->isEmpty())
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <p class="text-gray-500 text-lg">No rooms available. Add your first room!</p>
            </div>
        @endif
    </div>

    <!-- Room Modal -->
    @if($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-8 max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">
                    {{ $editMode ? 'Edit Room' : 'Add New Room' }}
                </h2>

                <form wire:submit.prevent="saveRoom">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Room Number</label>
                            <input 
                                type="text" 
                                wire:model="room_number"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            >
                            @error('room_number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Room Type</label>
                            <select 
                                wire:model="room_type_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            >
                                <option value="">Select Room Type</option>
                                @foreach($roomTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->type_name }}</option>
                                @endforeach
                            </select>
                            @error('room_type_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Price per Night</label>
                            <input 
                                type="number" 
                                step="0.01"
                                wire:model="price_per_night"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            >
                            @error('price_per_night') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select 
                                wire:model="status"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            >
                                <option value="available">Available</option>
                                <option value="occupied">Occupied</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                            @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea 
                                wire:model="description"
                                rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            ></textarea>
                            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Room Image</label>
                            <input 
                                type="file" 
                                wire:model="image"
                                accept="image/*"
                                class="w-full"
                            >
                            @if($existingImagePath && !$image)
                                <p class="text-sm text-gray-600 mt-2">Current image will be kept if not replaced</p>
                            @endif
                            @error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4 mt-6">
                        <button 
                            type="button"
                            wire:click="closeModal"
                            class="px-6 py-2 bg-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-400 transition"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit"
                            class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition"
                        >
                            {{ $editMode ? 'Update' : 'Create' }} Room
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Room Type Modal -->
    @if($showTypeModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-8 max-w-lg w-full max-h-[90vh] overflow-y-auto">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Add Room Type</h2>

                <form wire:submit.prevent="saveRoomType">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Type Name</label>
                            <input 
                                type="text" 
                                wire:model="type_name"
                                placeholder="e.g., Deluxe Suite"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            >
                            @error('type_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Capacity (Guests)</label>
                            <input 
                                type="number" 
                                wire:model="capacity"
                                min="1"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            >
                            @error('capacity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Amenities Section -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Amenities</label>
                            
                            <!-- Add Amenity Input -->
                            <div class="flex gap-2 mb-3">
                                <input 
                                    type="text" 
                                    wire:model="newAmenity"
                                    wire:keydown.enter.prevent="addAmenity"
                                    placeholder="Enter amenity (e.g., Wi-Fi, TV)"
                                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                >
                                <button 
                                    type="button"
                                    wire:click="addAmenity"
                                    class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition"
                                >
                                    Add
                                </button>
                            </div>

                            <!-- Amenities List -->
                            @if(count($amenities) > 0)
                                <div class="space-y-2">
                                    @foreach($amenities as $index => $amenity)
                                        <div class="flex items-center justify-between bg-gray-50 px-3 py-2 rounded-lg">
                                            <span class="text-gray-700">{{ $amenity }}</span>
                                            <button 
                                                type="button"
                                                wire:click="removeAmenity({{ $index }})"
                                                class="text-red-600 hover:text-red-800"
                                            >
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-500 italic">No amenities added yet</p>
                            @endif
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4 mt-6">
                        <button 
                            type="button"
                            wire:click="closeTypeModal"
                            class="px-6 py-2 bg-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-400 transition"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit"
                            class="px-6 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition"
                        >
                            Create Room Type
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>