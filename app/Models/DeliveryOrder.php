<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryOrder extends Model
{
    protected $fillable = [
        'shipping_note_id',
        'do_no',
        'inv_date',
        'customer_name',
        'address',
        'province',
        'warehouse_code',
        'delivery_date',
    ];

    public function shippingNote()
    {
        return $this->belongsTo(ShippingNote::class);
    }

    public function items()
    {
        return $this->hasMany(DeliveryOrderItem::class);
    }
}
