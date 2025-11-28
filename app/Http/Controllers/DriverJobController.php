<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShippingNote;
use Carbon\Carbon;

class DriverJobController extends Controller
{
    public function index()
    {
        // ดึง ShippingNote ทั้งหมด พร้อม DO และ Item
        $shippingNotes = ShippingNote::with(['deliveryOrders.items'])->get();

        // แปลงเป็น array ของ "งาน" ที่หน้า Blade ใช้อยู่
        $jobs = $shippingNotes->map(function ($note) {

            $orders = $note->deliveryOrders;

            // 1) รวมจังหวัดปลายทาง (ไม่ซ้ำ)
            $provinces = $orders
                ->pluck('province')
                ->filter()
                ->unique()
                ->implode(', ');

            // 2) วันที่ส่ง = max ของ delivery_date (ใน DO)
            $deliveryDate = $orders
                ->pluck('delivery_date')
                ->filter()
                ->max();

            // 3) จุดรับคร่าว ๆ: เอาจาก warehouse_code ตัวแรก (แล้วเติมชื่อคลังเอง)
            $firstOrder = $orders->first();
            $receiveLocation = $firstOrder?->warehouse_code
                ? $firstOrder->warehouse_code . ' MS ร่มเกล้า'
                : 'MS ร่มเกล้า';

            // 4) รวมจำนวนชิ้น + volume ทั้งงาน
            $allItems = $orders->flatMap->items;
            $totalQty    = $allItems->sum('qty');
            $totalWeight = $allItems->sum('volume');

            // 5) สรุป brand → จำนวน DO
            $brandSummary = $orders
                ->flatMap->items
                ->groupBy('brand')
                ->map(function ($group, $brand) {
                    return [
                        'brand'    => $brand ?? '-',
                        'do_count' => $group->pluck('delivery_order_id')->unique()->count(),
                    ];
                })
                ->values()
                ->all();

            // 6) เส้นทางคร่าว ๆ : จังหวัดเชื่อมด้วย →
            $roughRoute = str_replace(', ', ' → ', $provinces);

            // 7) Format วันที่รับจาก arrive_time
            $receiveDate = $note->arrive_time
                ? Carbon::parse($note->arrive_time)->format('d/m/Y')
                : '-';

            // 8) Format วันที่ส่ง
            $deliveryDateFormatted = $deliveryDate
                ? Carbon::parse($deliveryDate)->format('d/m/Y')
                : '-';

            // คืน object ให้ Blade ใช้เหมือน mock เดิม
            return (object) [
                'id'                 => $note->id,
                'job_number'         => $note->ship_no,
                'truck_type'         => $note->ship_type ?? '4w',   // ตอนนี้ default ไปก่อน
                'receive_date'       => $receiveDate,
                'receive_location'   => $receiveLocation,
                'delivery_date'      => $deliveryDateFormatted,
                'delivery_provinces' => $provinces,
                'total_qty'          => $totalQty ?: '-',
                'total_weight'       => $totalWeight ?: '-',
                'brand_summary'      => $brandSummary,
                'rough_route'        => $roughRoute,
            ];
        });

        return view('drivers_job_list', ['jobs' => $jobs]);
    }

    // ยัง mock ไว้เฉย ๆ
    public function show($id)
    {
        // ดึง ShippingNote พร้อม DO และ Items
        $job = ShippingNote::with(['deliveryOrders.items'])->findOrFail($id);

        // รวมจังหวัดปลายทาง (ไม่ซ้ำ)
        $provinces = $job->deliveryOrders
            ->pluck('province')
            ->filter()
            ->unique()
            ->implode(', ');

        // items ทั้งหมดของงานนี้
        $allItems = $job->deliveryOrders->flatMap->items;

        // จำนวนชิ้น / น้ำหนักรวม
        $totalQty    = $allItems->sum('qty');
        $totalWeight = $allItems->sum('volume');

        // สรุป Brand → จำนวน DO
        $brandSummary = $allItems
            ->groupBy('brand')
            ->map(function ($group, $brand) {
                return [
                    'brand'    => $brand ?? '-',
                    'do_count' => $group->pluck('delivery_order_id')->unique()->count(),
                ];
            })
            ->values()
            ->all();

        // เส้นทางคร่าว ๆ จากรายชื่อจังหวัด
        $roughRoute = str_replace(', ', ' → ', $provinces);

        // format เวลารับ/ส่ง ให้อ่านง่าย
        $job->arrive_time = $job->arrive_time
            ? Carbon::parse($job->arrive_time)->format('d/m/Y H:i')
            : '-';

        $job->depart_time = $job->depart_time
            ? Carbon::parse($job->depart_time)->format('d/m/Y H:i')
            : '-';

        return view('driver_job_detail', compact(
            'job',
            'provinces',
            'totalQty',
            'totalWeight',
            'brandSummary',
            'roughRoute'
        ));
    }

    public function accept($id)
    {
        // ไว้ทำ logic "รับงานนี้" ทีหลัง
        return redirect()->route('driver.jobs')
            ->with('success', "คุณรับงานเลขที่ {$id} แล้ว (mock)");
    }
}

