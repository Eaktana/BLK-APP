<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryOrderItem extends Model
{
    protected $fillable = [
        'delivery_order_id',
        'brand',
        'model',
        'volume',
        'qty',
    ];

    public function deliveryOrder()
    {
        return $this->belongsTo(DeliveryOrder::class);
    }
}
