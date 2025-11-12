<div class="min-h-screen bg-gray-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Reservation Management</h1>

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
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="flex border-b">
                <button 
                    wire:click="$set('filter', 'pending')"
                    class="px-6 py-3 font-semibold {{ $filter === 'pending' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-gray-900' }}"
                >
                    Pending
                </button>
                <button 
                    wire:click="$set('filter', 'confirmed')"
                    class="px-6 py-3 font-semibold {{ $filter === 'confirmed' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-gray-900' }}"
                >
                    Confirmed
                </button>
                <button 
                    wire:click="$set('filter', 'all')"
                    class="px-6 py-3 font-semibold {{ $filter === 'all' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-gray-900' }}"
                >
                    All
                </button>
                <button 
                    wire:click="$set('filter', 'cancelled')"
                    class="px-6 py-3 font-semibold {{ $filter === 'cancelled' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-gray-900' }}"
                >
                    Cancelled
                </button>
            </div>
        </div>

        <!-- Reservations Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Guest</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Room</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Check-in</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Check-out</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($reservations as $reservation)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #{{ $reservation->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $reservation->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $reservation->user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">Room {{ $reservation->room->room_number }}</div>
                                    <div class="text-sm text-gray-500">{{ $reservation->room->roomType->type_name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $reservation->check_in_date->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $reservation->check_out_date->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                    ${{ number_format($reservation->total_price, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        {{ $reservation->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $reservation->status === 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $reservation->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $reservation->status === 'completed' ? 'bg-blue-100 text-blue-800' : '' }}
                                    ">
                                        {{ ucfirst($reservation->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex space-x-2">
                                        <button 
                                            wire:click="viewDetails({{ $reservation->id }})"
                                            class="text-blue-600 hover:text-blue-800 font-medium"
                                        >
                                            View
                                        </button>

                                        @if($reservation->status === 'pending')
                                            <button 
                                                wire:click="approveReservation({{ $reservation->id }})"
                                                wire:confirm="Approve this reservation?"
                                                class="text-green-600 hover:text-green-800 font-medium"
                                            >
                                                Approve
                                            </button>
                                            <button 
                                                wire:click="denyReservation({{ $reservation->id }})"
                                                wire:confirm="Deny this reservation?"
                                                class="text-red-600 hover:text-red-800 font-medium"
                                            >
                                                Deny
                                            </button>
                                        @endif

                                        @if($reservation->status === 'confirmed' && $reservation->check_out_date->isPast())
                                            <button 
                                                wire:click="completeReservation({{ $reservation->id }})"
                                                class="text-blue-600 hover:text-blue-800 font-medium"
                                            >
                                                Complete
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($reservations->isEmpty())
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <p class="text-gray-500 text-lg">No reservations found</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Details Modal -->
    @if($showDetailsModal && $selectedReservation)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-8 max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-start mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Reservation Details</h2>
                    <button 
                        wire:click="closeModal"
                        class="text-gray-400 hover:text-gray-600"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="space-y-6">
                    <!-- Reservation Info -->
                    <div class="border-b pb-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Reservation Information</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Reservation ID</p>
                                <p class="font-semibold">#{{ $selectedReservation->id }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Status</p>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    {{ $selectedReservation->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $selectedReservation->status === 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $selectedReservation->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $selectedReservation->status === 'completed' ? 'bg-blue-100 text-blue-800' : '' }}
                                ">
                                    {{ ucfirst($selectedReservation->status) }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Check-in Date</p>
                                <p class="font-semibold">{{ $selectedReservation->check_in_date->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Check-out Date</p>
                                <p class="font-semibold">{{ $selectedReservation->check_out_date->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Number of Nights</p>
                                <p class="font-semibold">{{ $selectedReservation->number_of_nights }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Number of Guests</p>
                                <p class="font-semibold">{{ $selectedReservation->number_of_guests }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Guest Info -->
                    <div class="border-b pb-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Guest Information</h3>
                        <div class="space-y-2">
                            <div>
                                <p class="text-sm text-gray-600">Name</p>
                                <p class="font-semibold">{{ $selectedReservation->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Email</p>
                                <p class="font-semibold">{{ $selectedReservation->user->email }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Phone</p>
                                <p class="font-semibold">{{ $selectedReservation->user->phone_number ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Room Info -->
                    <div class="border-b pb-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Room Information</h3>
                        <div class="space-y-2">
                            <div>
                                <p class="text-sm text-gray-600">Room Number</p>
                                <p class="font-semibold">{{ $selectedReservation->room->room_number }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Room Type</p>
                                <p class="font-semibold">{{ $selectedReservation->room->roomType->type_name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Price per Night</p>
                                <p class="font-semibold">${{ number_format($selectedReservation->room->price_per_night, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Info -->
                    @if($selectedReservation->payment)
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Payment Information</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Room Total</span>
                                    <span class="font-semibold">${{ number_format($selectedReservation->total_price, 2) }}</span>
                                </div>
                                @if($selectedReservation->payment->extraCharges->count() > 0)
                                    @foreach($selectedReservation->payment->extraCharges as $charge)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">{{ $charge->service_name }}</span>
                                            <span class="font-semibold">${{ number_format($charge->price, 2) }}</span>
                                        </div>
                                    @endforeach
                                @endif
                                <div class="flex justify-between pt-2 border-t">
                                    <span class="text-lg font-bold">Total Amount</span>
                                    <span class="text-lg font-bold text-blue-600">
                                        ${{ number_format($selectedReservation->payment->total_amount, 2) }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Payment Method</span>
                                    <span class="font-semibold">{{ ucfirst(str_replace('_', ' ', $selectedReservation->payment->payment_method ?? 'N/A')) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Payment Status</span>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        {{ $selectedReservation->payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $selectedReservation->payment->status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $selectedReservation->payment->status === 'refunded' ? 'bg-red-100 text-red-800' : '' }}
                                    ">
                                        {{ ucfirst($selectedReservation->payment->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4 mt-6">
                    @if($selectedReservation->status === 'pending')
                        <button 
                            wire:click="approveReservation({{ $selectedReservation->id }})"
                            class="px-6 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition"
                        >
                            Approve
                        </button>
                        <button 
                            wire:click="denyReservation({{ $selectedReservation->id }})"
                            class="px-6 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition"
                        >
                            Deny
                        </button>
                    @endif
                    <button 
                        wire:click="closeModal"
                        class="px-6 py-2 bg-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-400 transition"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
