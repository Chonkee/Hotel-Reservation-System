<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_number',
        'room_type_id',
        'price_per_night',
        'status',
        'description',
        'image_path',
    ];

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function isAvailable($checkIn, $checkOut, $excludeReservationId = null)
    {
        if ($this->status !== 'available') {
            return false;
        }

        // Convert to Carbon instances if they're strings
        $checkIn = $checkIn instanceof \Carbon\Carbon ? $checkIn : \Carbon\Carbon::parse($checkIn);
        $checkOut = $checkOut instanceof \Carbon\Carbon ? $checkOut : \Carbon\Carbon::parse($checkOut);

        $query = $this->reservations()
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->whereBetween('check_in_date', [$checkIn->format('Y-m-d'), $checkOut->format('Y-m-d')])
                    ->orWhereBetween('check_out_date', [$checkIn->format('Y-m-d'), $checkOut->format('Y-m-d')])
                    ->orWhere(function ($q) use ($checkIn, $checkOut) {
                        $q->where('check_in_date', '<=', $checkIn->format('Y-m-d'))
                          ->where('check_out_date', '>=', $checkOut->format('Y-m-d'));
                    });
            });

        // Exclude specific reservation (useful when approving/editing)
        if ($excludeReservationId) {
            $query->where('id', '!=', $excludeReservationId);
        }

        return !$query->exists();
    }
}
