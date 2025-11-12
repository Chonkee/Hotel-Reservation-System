<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraCharge extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'service_name',
        'price',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
