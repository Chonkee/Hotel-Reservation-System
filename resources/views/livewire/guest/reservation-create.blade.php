<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">Complete Your Reservation</h2>

            @if(session()->has('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Booking Summary -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Room Details & Customization -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="border-b pb-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Booking Details</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Check-in</p>
                                <p class="font-semibold">{{ Carbon\Carbon::parse($checkInDate)->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Check-out</p>
                                <p class="font-semibold">{{ Carbon\Carbon::parse($checkOutDate)->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Nights</p>
                                <p class="font-semibold">{{ $numberOfNights }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Guests</p>
                                <p class="font-semibold">{{ $numberOfGuests }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Selected Rooms with Customization -->
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Selected Rooms</h3>
                        
                        @foreach($selectedRooms as $room)
                            <div class="border rounded-lg p-6 mb-4">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h4 class="text-lg font-bold text-gray-900">Room {{ $room->room_number }}</h4>
                                        <p class="text-gray-600">{{ $room->roomType->type_name }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-600">{{ $numberOfNights }} nights</p>
                                        <p class="text-xl font-bold text-blue-600">
                                            ${{ number_format($room->price_per_night * $numberOfNights, 2) }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Room Customization Options -->
                                <div class="border-t pt-4">
                                    <h5 class="font-semibold text-gray-900 mb-3">Customize Your Room</h5>
                                    
                                    <div class="space-y-4">
                                        <!-- Bed Type -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Bed Preference
                                            </label>
                                            <select 
                                                wire:change="updateCustomization({{ $room->id }}, 'bed_type', $event.target.value)"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                            >
                                                <option value="">No Preference</option>
                                                <option value="king">King Size Bed</option>
                                                <option value="queen">Queen Size Bed</option>
                                                <option value="twin">Twin Beds</option>
                                            </select>
                                        </div>

                                        <!-- Floor Preference -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Floor Preference
                                            </label>
                                            <select 
                                                wire:change="updateCustomization({{ $room->id }}, 'floor', $event.target.value)"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                            >
                                                <option value="">No Preference</option>
                                                <option value="lower">Lower Floor (1-5)</option>
                                                <option value="middle">Middle Floor (6-10)</option>
                                                <option value="upper">Upper Floor (11+)</option>
                                            </select>
                                        </div>

                                        <!-- Special Requests -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Special Requests
                                            </label>
                                            <textarea 
                                                wire:change="updateCustomization({{ $room->id }}, 'special_requests', $event.target.value)"
                                                rows="3"
                                                placeholder="Early check-in, extra pillows, etc."
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                            ></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Right Column - Payment Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-gray-50 rounded-lg p-6 sticky top-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">Payment Summary</h3>

                        <div class="space-y-3 mb-6">
                            @foreach($selectedRooms as $room)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Room {{ $room->room_number }} ({{ $numberOfNights }}x)</span>
                                    <span class="font-semibold">${{ number_format($room->price_per_night * $numberOfNights, 2) }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="border-t pt-4 mb-6">
                            <div class="flex justify-between text-lg font-bold">
                                <span>Total</span>
                                <span class="text-blue-600">${{ number_format($totalPrice, 2) }}</span>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Payment Method
                            </label>
                            <select 
                                wire:model="paymentMethod"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            >
                                <option value="credit_card">Credit Card</option>
                                <option value="debit_card">Debit Card</option>
                                <option value="paypal">PayPal</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="cash">Cash (Pay at Hotel)</option>
                            </select>
                        </div>

                        <button 
                            wire:click="createReservation"
                            class="w-full px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition"
                        >
                            Confirm Reservation
                        </button>

                        <p class="text-xs text-gray-500 mt-4 text-center">
                            Your reservation will be pending until confirmed by our staff.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>