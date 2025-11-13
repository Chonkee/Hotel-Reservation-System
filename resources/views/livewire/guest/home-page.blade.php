<div class="min-h-screen bg-white">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl md:text-6xl font-bold text-white mb-6">
                Welcome to Grand Vista Hotel
            </h1>
            <p class="text-xl md:text-2xl text-blue-100 mb-10 max-w-3xl mx-auto">
                Experience luxury and comfort in the heart of the city. Your perfect getaway awaits.
            </p>
            @auth
                <a href="{{ route('rooms.search') }}" class="inline-block px-8 py-4 bg-white text-blue-700 font-semibold text-lg rounded-lg hover:bg-blue-50 transition shadow-lg">
                    Book Your Stay
                </a>
            @else
                <a href="{{ route('login') }}" class="inline-block px-8 py-4 bg-white text-blue-700 font-semibold text-lg rounded-lg hover:bg-blue-50 transition shadow-lg">
                    Book Your Stay
                </a>
            @endauth
        </div>
    </section>

    <!-- About Our Hotel Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-12">About Our Hotel</h2>
            
            <div class="bg-white rounded-2xl shadow-md p-8 md:p-12 mb-12">
                <p class="text-lg text-gray-700 leading-relaxed mb-8">
                    Grand Vista Hotel offers a perfect blend of luxury, comfort, and convenience. Located in the heart of the city, we provide our guests with exceptional service, modern amenities, and spacious accommodations. Whether you're traveling for business or leisure, our hotel is your home away from home.
                </p>

                <!-- Amenities Grid -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    <!-- Free WiFi -->
                    <div class="text-center">
                        <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Free WiFi</h3>
                    </div>

                    <!-- Free Parking -->
                    <div class="text-center">
                        <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Free Parking</h3>
                    </div>

                    <!-- Restaurant -->
                    <div class="text-center">
                        <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Restaurant</h3>
                    </div>

                    <!-- Fitness Center -->
                    <div class="text-center">
                        <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Fitness Center</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Rooms Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900">Our Rooms</h2>
                <a href="{{ route('rooms.browse') }}" class="px-6 py-3 bg-black text-white font-semibold rounded-lg hover:bg-gray-800 transition">
                    View All Rooms
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($roomTypes as $roomType)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition border border-gray-100">
                        <!-- Room Image Placeholder -->
                        <div class="h-48 bg-gray-200 flex items-center justify-center">
                            @if($roomType->rooms->first() && $roomType->rooms->first()->image_path)
                                <img src="{{ asset('storage/' . $roomType->rooms->first()->image_path) }}" 
                                     alt="{{ $roomType->type_name }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <svg class="w-20 h-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            @endif
                        </div>

                        <!-- Room Details -->
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $roomType->type_name }}</h3>
                            <p class="text-gray-600 text-sm mb-4">{{ ucfirst(str_replace('_', ' ', strtolower($roomType->type_name))) }}</p>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-gray-600 text-sm">
                                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <span>{{ $roomType->capacity }} Guests</span>
                                </div>
                                <div class="text-right">
                                    @if($roomType->rooms->count() > 0)
                                        <p class="text-2xl font-bold text-blue-600">
                                            ${{ number_format($roomType->rooms->min('price_per_night'), 0) }}
                                        </p>
                                        <p class="text-xs text-gray-500">/night</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Contact Us Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-12">Contact Us</h2>
            
            <div class="bg-white rounded-2xl shadow-md p-8 md:p-12">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Address -->
                    <div class="flex items-start">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Address</h3>
                            <p class="text-gray-600">123 Grand Avenue, City Center, ST 12345</p>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="flex items-start">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Phone</h3>
                            <p class="text-gray-600">+1 (555) 123-4567</p>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="flex items-start">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Email</h3>
                            <p class="text-gray-600">info@grandvista.com</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-gray-400">© {{ date('Y') }} Grand Vista Hotel. All rights reserved.</p>
        </div>
    </footer>
</div>