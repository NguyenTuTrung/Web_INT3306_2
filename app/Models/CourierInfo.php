<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourierInfo extends Model
{
    use HasFactory;


    public function senderStaff()
    {
        return $this->belongsTo(User::class, 'sender_staff_id');
    }

    public function senderStaffBranch()
    {
        return $this->belongsTo(User::class, 'sender_staff_branch');
    }

    public function receiverStaff()
    {
        return $this->belongsTo(User::class, 'receiver_staff_id');
    }


    public function receiverBranch()
    {
        return $this->belongsTo(Branch::class, 'receiver_branch_id');
    }

    public function senderBranch()
    {
        return $this->belongsTo(Branch::class, 'sender_branch_id');
    }

    public function receiverWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'receiver_warehouse_id');
    }

    public function senderWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'sender_warehouse_id');
    }


    public function paymentInfo()
    {
        return $this->hasOne(CourierPayment::class, 'courier_info_id');
    }


    public function courierDetail()
    {
        return $this->hasMany(CourierProduct::class, 'courier_info_id')->with('type');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
