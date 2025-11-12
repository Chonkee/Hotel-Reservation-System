<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Our Rooms</h1>
            <p class="text-lg text-gray-600">Discover comfort and luxury in our carefully designed rooms</p>
            
            @auth
                <div class="mt-6">
                    <a href="{{ route('rooms.search') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Search Available Rooms
                    </a>
                </div>
            @else
                <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-blue-800">
                        Please <a href="{{ route('login') }}" class="font-semibold underline">log in</a> to book a reservation
                    </p>
                </div>
            @endauth
        </div>

        <!-- Room Types Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($roomTypes as $roomType)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
                    <!-- Room Type Image -->
                    <div class="h-48 bg-gray-300 relative">
                        @if($roomType->rooms->first() && $roomType->rooms->first()->image_path)
                            <img src="{{ asset('storage/' . $roomType->rooms->first()->image_path) }}" 
                                 alt="{{ $roomType->type_name }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="flex items-center justify-center h-full">
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Room Type Info -->
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $roomType->type_name }}</h3>
                        
                        <div class="flex items-center text-gray-600 mb-4">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span>Capacity: {{ $roomType->capacity }} guests</span>
                        </div>

                        <!-- Amenities -->
                        @if($roomType->amenities)
                            <div class="mb-4">
                                <h4 class="font-semibold text-gray-900 mb-2">Amenities:</h4>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($roomType->amenities_array as $amenity)
                                        <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded-full">
                                            {{ $amenity }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Price Range -->
                        @if($roomType->rooms->count() > 0)
                            <div class="mb-4">
                                <p class="text-sm text-gray-600">Starting from</p>
                                <p class="text-3xl font-bold text-blue-600">
                                    ${{ number_format($roomType->rooms->min('price_per_night'), 2) }}
                                </p>
                                <p class="text-sm text-gray-600">per night</p>
                            </div>
                        @endif

                        <!-- Available Rooms Count -->
                        <div class="text-sm text-gray-600 mb-4">
                            {{ $roomType->rooms->where('status', 'available')->count() }} rooms available
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($roomTypes->isEmpty())
            <div class="text-center py-12">
                <p class="text-gray-500 text-lg">No room types available at the moment.</p>
            </div>
        @endif
    </div>
</div>
