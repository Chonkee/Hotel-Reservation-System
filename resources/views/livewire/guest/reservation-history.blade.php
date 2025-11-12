<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">My Reservations</h2>

            @if(session()->has('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session()->has('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Filter Tabs -->
            <div class="flex space-x-4 mb-8 border-b">
                <button 
                    wire:click="$set('filter', 'all')"
                    class="px-4 py-2 font-semibold {{ $filter === 'all' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-gray-900' }}"
                >
                    All
                </button>
                <button 
                    wire:click="$set('filter', 'current')"
                    class="px-4 py-2 font-semibold {{ $filter === 'current' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-gray-900' }}"
                >
                    Current
                </button>
                <button 
                    wire:click="$set('filter', 'past')"
                    class="px-4 py-2 font-semibold {{ $filter === 'past' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-gray-900' }}"
                >
                    Past
                </button>
                <button 
                    wire:click="$set('filter', 'cancelled')"
                    class="px-4 py-2 font-semibold {{ $filter === 'cancelled' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-gray-900' }}"
                >
                    Cancelled
                </button>
            </div>

            <!-- Reservations List -->
            @if($reservations->count() > 0)
                <div class="space-y-6">
                    @foreach($reservations as $reservation)
                        <div class="border rounded-lg p-6 hover:shadow-md transition">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h3 class="text-xl font-bold text-gray-900">
                                            Room {{ $reservation->room->room_number }}
                                        </h3>
                                        <span class="px-3 py-1 text-sm font-semibold rounded-full
                                            {{ $reservation->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $reservation->status === 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $reservation->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                            {{ $reservation->status === 'completed' ? 'bg-blue-100 text-blue-800' : '' }}
                                        ">
                                            {{ ucfirst($reservation->status) }}
                                        </span>
                                    </div>
                                    <p class="text-gray-600">{{ $reservation->room->roomType->type_name }}</p>
                                </div>

                                <div class="text-right">
                                    <p class="text-2xl font-bold text-blue-600">
                                        ${{ number_format($reservation->total_price, 2) }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        @if($reservation->payment)
                                            Payment: {{ ucfirst($reservation->payment->status) }}
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                <div>
                                    <p class="text-sm text-gray-600">Check-in</p>
                                    <p class="font-semibold">{{ $reservation->check_in_date->format('M d, Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Check-out</p>
                                    <p class="font-semibold">{{ $reservation->check_out_date->format('M d, Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Nights</p>
                                    <p class="font-semibold">{{ $reservation->number_of_nights }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Guests</p>
                                    <p class="font-semibold">{{ $reservation->number_of_guests }}</p>
                                </div>
                            </div>

                            <div class="flex justify-between items-center pt-4 border-t">
                                <p class="text-sm text-gray-500">
                                    Booked on {{ $reservation->created_at->format('M d, Y') }}
                                </p>

                                @if($reservation->status === 'pending')
                                    <button 
                                        wire:click="cancelReservation({{ $reservation->id }})"
                                        wire:confirm="Are you sure you want to cancel this reservation?"
                                        class="px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition"
                                    >
                                        Cancel Reservation
                                    </button>
                                @endif

                                @if($reservation->status === 'completed')
                                    <a 
                                        href="#"
                                        class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition"
                                    >
                                        Leave a Review
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <p class="text-gray-500 text-lg">No reservations found</p>
                    <p class="text-gray-400 mt-2">Start by browsing our available rooms</p>
                    <a 
                        href="{{ route('rooms.search') }}"
                        class="inline-block mt-4 px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition"
                    >
                        Browse Rooms
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
