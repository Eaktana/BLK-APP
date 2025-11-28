<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShippingNote;
use App\Models\DeliveryOrder;
use App\Models\DeliveryOrderItem;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // mapping brand → คลังที่ใช้ได้
        $brandWarehouses = [
            'HIFI'      => ['501', '502'],
            'Aconatic'  => ['501', '503'],
            'Samsung'   => ['501'],
            'Sharp'     => ['502', '503'],
            'Panasonic' => ['503'],
            'LG'        => ['501', '502', '503'],
        ];

        // สร้าง 30 shipping notes
        for ($s = 1; $s <= 30; $s++) {

            $ship = ShippingNote::create([
                'ship_no'     => 'S-' . str_pad($s, 6, '0', STR_PAD_LEFT),
                'ship_type'   => 'CBM',
                'planner'     => fake()->randomElement(['Cdp', 'Wiparat', 'Admin']),
                'route_name'  => 'Route-' . fake()->randomElement(['A', 'B', 'C', 'D']),
                'arrive_time' => now()->subHours(rand(1, 5)),
                'depart_time' => now()->addHours(rand(2, 8)),
            ]);

            // สุ่มจำนวน DO 3–6 DO ต่อ 1 shipping note
            $doCount = rand(3, 6);

            for ($d = 1; $d <= $doCount; $d++) {

                // สุ่ม brand 1 อัน / DO
                $brandName = array_rand($brandWarehouses);

                // เลือกคลังจาก brand นั้น
                $wh = $brandWarehouses[$brandName];
                $warehouse = $wh[array_rand($wh)];

                // สร้าง DO
                $do = DeliveryOrder::create([
                    'shipping_note_id' => $ship->id,
                    'do_no'            => 'IN' . str_pad($s, 4, '0', STR_PAD_LEFT) . str_pad($d, 3, '0', STR_PAD_LEFT),
                    'inv_date'         => now()->subDays(rand(0, 7))->toDateString(),
                    'customer_name'    => 'บริษัท ทดสอบลูกค้า ' . $d,
                    'address'          => 'ที่อยู่ลูกค้า ' . $d . ' ต.ทดสอบ อ.เมือง จ.กรุงเทพมหานคร ' . rand(10100, 10200),
                    'province'         => 'กรุงเทพมหานคร',
                    'warehouse_code'   => $warehouse,
                    'delivery_date'    => now()->addDays(rand(1, 7))->toDateString(),
                ]);

                // จำนวน item / DO = 5–10 รุ่น
                $itemCount = rand(5, 10);

                for ($i = 1; $i <= $itemCount; $i++) {

                    DeliveryOrderItem::create([
                        'delivery_order_id' => $do->id,
                        'brand'  => $brandName,   // ทุก item ใน DO = brand เดียวกัน
                        'model'  => "MODEL-{$d}-{$i}",
                        'volume' => fake()->randomFloat(4, 0.1, 1.5),
                        'qty'    => fake()->numberBetween(1, 50),
                    ]);
                }
            }
        }
    }
}
