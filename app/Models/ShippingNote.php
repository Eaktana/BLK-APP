<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingNote extends Model
{
    protected $fillable = [
        'ship_no',
        'ship_type',
        'planner',
        'route_name',
        'arrive_time',
        'depart_time',
    ];

    public function deliveryOrders()
    {
        return $this->hasMany(DeliveryOrder::class);
    }
}
