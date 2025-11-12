<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_name',
        'capacity',
        'amenities',
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function getAmenitiesArrayAttribute()
    {
        return $this->amenities ? json_decode($this->amenities, true) : [];
    }

    public function setAmenitiesAttribute($value)
    {
        $this->attributes['amenities'] = is_array($value) ? json_encode($value) : $value;
    }
}
