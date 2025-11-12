<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\RoomType;
use App\Models\Room;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@hotel.com',
            'password' => Hash::make('password'),
            'phone_number' => '123-456-7890',
            'address' => '123 Admin Street',
            'role' => 'Admin',
        ]);

        // Create Guest Users
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'phone_number' => '123-456-7891',
            'address' => '456 Guest Avenue',
            'role' => 'Guest',
        ]);

        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
            'phone_number' => '123-456-7892',
            'address' => '789 Guest Boulevard',
            'role' => 'Guest',
        ]);

        // Create Room Types
        $standardRoom = RoomType::create([
            'type_name' => 'Standard Room',
            'capacity' => 2,
            'amenities' => json_encode(['Wi-Fi', 'TV', 'Air Conditioning', 'Mini Bar']),
        ]);

        $deluxeRoom = RoomType::create([
            'type_name' => 'Deluxe Room',
            'capacity' => 3,
            'amenities' => json_encode(['Wi-Fi', 'TV', 'Air Conditioning', 'Mini Bar', 'Ocean View', 'Balcony']),
        ]);

        $suite = RoomType::create([
            'type_name' => 'Executive Suite',
            'capacity' => 4,
            'amenities' => json_encode(['Wi-Fi', 'TV', 'Air Conditioning', 'Mini Bar', 'Ocean View', 'Balcony', 'Jacuzzi', 'Living Room']),
        ]);

        $familyRoom = RoomType::create([
            'type_name' => 'Family Room',
            'capacity' => 5,
            'amenities' => json_encode(['Wi-Fi', 'TV', 'Air Conditioning', 'Mini Bar', 'Kitchen', 'Extra Beds']),
        ]);

        // Create Standard Rooms
        for ($i = 101; $i <= 110; $i++) {
            Room::create([
                'room_number' => (string)$i,
                'room_type_id' => $standardRoom->id,
                'price_per_night' => 100.00,
                'status' => 'available',
                'description' => 'Comfortable standard room with all basic amenities for a pleasant stay.',
            ]);
        }

        // Create Deluxe Rooms
        for ($i = 201; $i <= 208; $i++) {
            Room::create([
                'room_number' => (string)$i,
                'room_type_id' => $deluxeRoom->id,
                'price_per_night' => 150.00,
                'status' => 'available',
                'description' => 'Spacious deluxe room with beautiful ocean views and premium amenities.',
            ]);
        }

        // Create Executive Suites
        for ($i = 301; $i <= 305; $i++) {
            Room::create([
                'room_number' => (string)$i,
                'room_type_id' => $suite->id,
                'price_per_night' => 250.00,
                'status' => 'available',
                'description' => 'Luxurious executive suite with separate living area and top-tier amenities.',
            ]);
        }

        // Create Family Rooms
        for ($i = 401; $i <= 405; $i++) {
            Room::create([
                'room_number' => (string)$i,
                'room_type_id' => $familyRoom->id,
                'price_per_night' => 180.00,
                'status' => 'available',
                'description' => 'Perfect for families with multiple beds and a kitchenette.',
            ]);
        }

        // Set one room to occupied and one to maintenance for demo
        Room::where('room_number', '101')->update(['status' => 'occupied']);
        Room::where('room_number', '201')->update(['status' => 'maintenance']);
    }
}