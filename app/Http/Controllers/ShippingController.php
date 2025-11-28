<?php

namespace App\Http\Controllers;

use App\Models\ShippingNote;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    private $brandColors = [
        'HIFI'      => '#fff3cd',
        'Aconatic'  => '#e3f2fd',
        'Samsung'   => '#e8f5e9',
        'Sharp'     => '#fce4ec',
        'Panasonic' => '#ede7f6',
        'LG'        => '#f1f8e9',
    ];

    public function show($id)
    {
        $shipping = ShippingNote::with('deliveryOrders.items')->findOrFail($id);

        // 1 DO = 1 brand
        $shipping->deliveryOrders->each(function ($do) {
            $do->brand_name = optional($do->items->first())->brand;
        });

        // 🧠 ลำดับที่ต้องการ:
        // กลุ่มคลังเดียวกัน -> กำหนดส่ง -> กำหนดรับ
        $shipping->deliveryOrders = $shipping->deliveryOrders
            ->sortBy('inv_date')        // กำหนดรับ (ชั้นในสุด)
            ->sortBy('delivery_date')   // กำหนดส่ง
            ->sortBy('warehouse_code')  // คลัง (ชั้นนอกสุด)
            ->values();

        return view('shipping_note', [
            'shipping'    => $shipping,
            'brandColors' => $this->brandColors,
        ]);
    }
}