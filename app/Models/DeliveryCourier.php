<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryCourier extends Model
{
        use HasFactory;
        
        public function courierInfo()
        {
                return $this->belongsTo(CourierInfo::class, 'courier_id');
        }

}