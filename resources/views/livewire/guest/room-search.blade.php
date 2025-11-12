<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Search Form -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Search Available Rooms</h2>
            
            <form wire:submit.prevent="searchRooms">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Check-in Date -->
                    <div>
                        <label for="checkInDate" class="block text-sm font-medium text-gray-700 mb-2">
                            Check-in Date
                        </label>
                        <input 
                            type="date" 
                            id="checkInDate"
                            wire:model="checkInDate"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                        @error('checkInDate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Check-out Date -->
                    <div>
                        <label for="checkOutDate" class="block text-sm font-medium text-gray-700 mb-2">
                            Check-out Date
                        </label>
                        <input 
                            type="date" 
                            id="checkOutDate"
                            wire:model="checkOutDate"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                        @error('checkOutDate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Number of Guests -->
                    <div>
                        <label for="numberOfGuests" class="block text-sm font-medium text-gray-700 mb-2">
                            Guests
                        </label>
                        <input 
                            type="number" 
                            id="numberOfGuests"
                            wire:model="numberOfGuests"
                            min="1"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                        @error('numberOfGuests') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Number of Rooms -->
                    <div>
                        <label for="numberOfRooms" class="block text-sm font-medium text-gray-700 mb-2">
                            Rooms
                        </label>
                        <input 
                            type="number" 
                            id="numberOfRooms"
                            wire:model="numberOfRooms"
                            min="1"
                            max="10"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                        @error('numberOfRooms') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <button 
                        type="submit"
                        class="w-full md:w-auto px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition"
                    >
                        Search Rooms
                    </button>
                </div>
            </form>
        </div>

        <!-- Search Results -->
        @if($searchPerformed)
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">
                        Available Rooms
                        @if(count($selectedRooms) > 0)
                            <span class="text-sm text-gray-600 ml-2">
                                ({{ count($selectedRooms) }} / {{ $numberOfRooms }} selected)
                            </span>
                        @endif
                    </h2>

                    @if(count($selectedRooms) === $numberOfRooms)
                        <button 
                            wire:click="proceedToBooking"
                            class="px-6 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition"
                        >
                            Proceed to Booking
                        </button>
                    @endif
                </div>

                @if(session()->has('error'))
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif

                @if($groupedRooms->count() > 0)
                    @foreach($groupedRooms as $roomTypeId => $rooms)
                        <div class="mb-8">
                            <h3 class="text-xl font-bold text-gray-800 mb-4">
                                {{ $rooms->first()->roomType->type_name }}
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($rooms as $room)
                                    <div 
                                        wire:click="toggleRoomSelection({{ $room->id }})"
                                        class="border-2 rounded-lg p-4 cursor-pointer transition {{ in_array($room->id, $selectedRooms) ? 'border-blue-600 bg-blue-50' : 'border-gray-200 hover:border-blue-400' }}"
                                    >
                                        <!-- Room Image -->
                                        <div class="h-40 bg-gray-200 rounded-lg mb-4 overflow-hidden">
                                            @if($room->image_path)
                                                <img src="{{ asset('storage/' . $room->image_path) }}" 
                                                     alt="Room {{ $room->room_number }}" 
                                                     class="w-full h-full object-cover">
                                            @else
                                                <div class="flex items-center justify-center h-full">
                                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="flex justify-between items-start mb-2">
                                            <h4 class="font-bold text-gray-900">Room {{ $room->room_number }}</h4>
                                            @if(in_array($room->id, $selectedRooms))
                                                <span class="px-2 py-1 bg-blue-600 text-white text-xs rounded-full">Selected</span>
                                            @endif
                                        </div>

                                        <p class="text-sm text-gray-600 mb-2">{{ $room->description }}</p>
                                        
                                        <div class="flex items-center justify-between">
                                            <span class="text-2xl font-bold text-blue-600">
                                                ${{ number_format($room->price_per_night, 2) }}
                                            </span>
                                            <span class="text-sm text-gray-600">per night</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-gray-500 text-lg">No rooms available for the selected dates and criteria.</p>
                        <p class="text-gray-400 mt-2">Try adjusting your search parameters.</p>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>