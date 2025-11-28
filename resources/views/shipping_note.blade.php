<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รายละเอียดงาน</title>
    <link rel="icon" type="image/png" href="/favicon.png">

    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            font-size: 0.8rem;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #333;
            padding: 5px;
            vertical-align: top;
        }
        th {
            background: #f3f3f3;
            font-weight: bold;
            text-align: center;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .text-red { color: red; }
        .barcode {
            font-size: 10px;
            text-align: center;
        }
        .page-title {
            display: flex;
            text-align: center;
            margin-bottom: 10px;
            font-size: 1.5rem;
            font-weight: bold;
        }
        .page-title div {
            flex-grow: 1;
            align-self: center;
        }
        .container {
            width: 95%;
            margin: auto;
        }
        .p-td {
            margin-top: 0;
        }

        .text-link {
            text-decoration: none;
        }

        .container-header {
            margin-bottom: 15px;
        }

        .table-no-border td,
        .table-no-border th {
            border: none !important;
        }

        .font-header {
            font-size: 1rem;
        }

        .header-detail {
            display: flex;
            align-items: center;
        }


    </style>
</head>

<body>
    <div class="container">
        <div class="container-header">
            <div class="page-title">
                <img src="{{asset('images/BKL_HEADER.png') }}" alt="" class="img-header"  style="width:200px; height:auto;">
                <div>เอกสารจัดส่งสินค้า (Shipping Note)</div>
            </div>
            <div class="header-detail">
                <table class="table-no-border font-header">
                    <tr>
                        <td><strong>เลขที่งาน:</strong> S-175026</td>
                        <td><strong>วันที่สร้าง:</strong> 2025-11-25</td>
                        <td><strong>ประเภทส่ง:</strong> CBM</td>
                        <td><strong>Planner:</strong> Cdp</td>
                    </tr>
                    <tr>
                        <td><strong>รถเข้าคลัง:</strong> 26-Nov 11:00</td>
                        <td><strong>รถออกคลัง:</strong> 26-Nov 18:03</td>
                        <td colspan="2"><strong>เส้นทาง:</strong> กอ38=>ขัวปันยอม</td>
                    </tr>
                </table>
            </div>
        </div>
        <div>
            <table>
                <thead>
                    <tr>
                        <th>ลำดับ</th>
                        <th>หมายเลข DO</th>
                        <th>BRAND</th>
                        <th>MODEL</th>
                        <th>ความจุ</th>
                        <th>จำนวน</th>
                        <th>ชื่อลูกค้า</th>
                        <th>ที่อยู่</th>
                        <th>จังหวัด</th>
                        <th>คลังสินค้า</th>
                        <th>กำหนดรับ</th>
                        <th>กำหนดส่ง</th>
                        <th>หมายเหตุสินค้า</th>
                    </tr>
                </thead>
        
                <tbody>
    
                @php $index = 1; @endphp

                @foreach ($shipping->deliveryOrders as $do)
                    @php
                        $itemCount = $do->items->count();
                        $brand = $do->brand_name;
                        $rowColor = $brandColors[$brand] ?? '';   // สีของ brand นี้
                    @endphp

                    @foreach ($do->items as $i => $item)
                        <tr @if($rowColor) style="background-color: {{ $rowColor }};" @endif>

                            @if ($i == 0)
                                <td rowspan="{{ $itemCount }}" class="text-center">{{ $index }}</td>
                                <td rowspan="{{ $itemCount }}" class="text-center">
                                    <div class="barcode">||| || || |</div>
                                    {{ $do->do_no }}
                                </td>
                            @endif

                            <td>{{ $item->brand }}</td>
                            <td>{{ $item->model }}</td>
                            <td class="text-right">{{ $item->volume }}</td>
                            <td class="text-right">{{ $item->qty }}</td>

                            @if ($i == 0)
                                <td rowspan="{{ $itemCount }}">{{ $do->customer_name }}</td>
                                <td rowspan="{{ $itemCount }}">
                                    <p class="p-td">{{ $do->address }}</p>
                                    <a href="https://maps.google.com/?q={{ urlencode($do->address) }}"
                                    class="text-link" target="_blank">
                                        เปิดบน Google Maps
                                    </a>
                                </td>
                                <td rowspan="{{ $itemCount }}">{{ $do->province }}</td>
                                <td rowspan="{{ $itemCount }}">{{ $do->warehouse_code }}</td>
                                <td rowspan="{{ $itemCount }}">{{ $do->inv_date }}</td>
                                <td rowspan="{{ $itemCount }}">{{ $do->delivery_date }}</td>
                                <td rowspan="{{ $itemCount }}">{{ $do->note ?? '' }}</td>
                            @endif

                        </tr>
                    @endforeach

                    @php $index++; @endphp
                @endforeach

    
                </tbody>
                @php
                    $totalVolume = 0;
                    $totalQty = 0;

                    foreach ($shipping->deliveryOrders as $do) {
                        foreach ($do->items as $item) {
                            $totalVolume += $item->volume;
                            $totalQty += $item->qty;
                        }
                    }
                @endphp
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-center">summary</td>
                        <td>{{ number_format($totalVolume, 4) }}</td>
                        <td>{{ number_format($totalQty) }}</td>
                        <td colspan="7"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>
</html>
