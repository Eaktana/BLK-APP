<!DOCTYPE html>
<html lang="th" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายละเอียดงานขนส่ง</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/driver_register.css'])
</head>

<body class="py-4">

<div class="container mb-4">

    {{-- Back button --}}
    <a href="{{ route('driver.jobs') }}" class="btn btn-outline-secondary mb-3">
        <i class="bi bi-arrow-left"></i> ย้อนกลับ
    </a>

    <h1 class="display-6 fw-bold mb-4">
        รายละเอียด<span class="text-primary">งานขนส่ง</span>
    </h1>

    {{-- Summary Card --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body p-4">

            <div class="d-flex justify-content-between mb-3">
                <div class="fw-semibold" style="font-size: 1.2rem;">
                    เลขที่งาน: {{ $job->ship_no }}
                </div>
                <div class="fw-semibold text-end">
                    {{ $job->ship_type }}
                </div>
            </div>

            <div class="card p-3 mb-3">
                <p class="mb-1">เวลารับ : {{ $job->arrive_time }}</p>
                <p class="mb-0">สถานที่รับ : {{ $job->route_name }}</p>
            </div>

            <div class="card p-3 mb-3">
                <p class="mb-1">เวลาส่ง : {{ $job->depart_time }}</p>
                <p class="mb-0">สถานที่ส่ง : {{ $provinces }}</p>
            </div>

            <p class="ms-3 mb-1">จำนวนชิ้นรวม : {{ $totalQty }}</p>
            <p class="ms-3">น้ำหนักรวม : {{ $totalWeight }}</p>

        </div>
    </div>

    {{-- Brand Summary --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body p-4">

            <h5 class="fw-semibold mb-3">ลูกค้าที่ต้องส่งให้</h5>

            <table class="table table-sm brand-table small align-middle">
                <thead>
                    <tr>
                        <th>Brand</th>
                        <th class="text-end">จำนวน DO</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($brandSummary as $row)
                        <tr>
                            <td>{{ $row['brand'] }}</td>
                            <td class="text-end">{{ $row['do_count'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <hr class="text-secondary my-3">

            <h6 class="fw-semibold mb-1">เส้นทางคร่าว ๆ</h6>
            <p class="small">{{ $roughRoute }}</p>

        </div>
    </div>

    {{-- DO + Items Detail --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body p-3">

            <h5 class="fw-semibold mb-3">ข้อมูล Delivery Order ทั้งหมด</h5>
            <div class="table-responsive d-none d-md-block">
                <table class="table table-sm brand-table small align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>หมายเลข DO</th>
                            <th>Brand</th>
                            <th>Model</th>
                            <th>ความจุ</th>
                            <th>จำนวน</th>
                            <th>ชื่อลูกค้า</th>
                            <th>ที่อยู่</th>
                            <th>จังหวัด</th>
                            <th>คลังสินค้า</th>
                            <th>กำหนดรับ</th>
                            <th>กำหนดส่ง</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // ลำดับแถวรวม (1, 2, 3, 4, ...)
                            $rowNumber = 1;
                        @endphp
    
                        @foreach ($job->deliveryOrders as $do)
                            @foreach ($do->items as $idx => $item)
                                <tr>
                                    {{-- ลำดับรันรวม 1, 2, 3, 4 ... --}}
                                    <td>{{ $rowNumber++ }}</td>
    
                                    {{-- ให้แสดง DO / Brand / ลูกค้า / ที่อยู่ ฯลฯ เฉพาะแถวแรกของ DO --}}
                                    <td>{{ $idx === 0 ? $do->do_no : '' }}</td>
                                    <td>{{ $idx === 0 ? $item->brand : '' }}</td>
    
                                    {{-- Model แสดงทุกแถว เพราะแต่ละ model ต่างกัน --}}
                                    <td>{{ $item->model }}</td>
    
                                    <td>
                                        {{ $item->volume !== null ? number_format($item->volume, 4) : '-' }}
                                    </td>
                                    <td>{{ $item->qty }}</td>
    
                                    <td>{{ $idx === 0 ? $do->customer_name : '' }}</td>
                                    <td>{{ $idx === 0 ? $do->address : '' }}</td>
                                    <td>{{ $idx === 0 ? $do->province : '' }}</td>
                                    <td>{{ $idx === 0 ? $do->warehouse_code : '' }}</td>
                                    <td>{{ $idx === 0 ? $do->inv_date : '' }}</td>
                                    <td>{{ $idx === 0 ? $do->delivery_date : '' }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- แสดงเฉพาะจอเล็ก (มือถือ) --}}
            <div class="d-block d-md-none">
                @php $rowNumber = 1; @endphp

                @foreach($job->deliveryOrders as $do)
                    <div class="card mb-3 shadow-sm">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="small text-secondary">DO #{{ $rowNumber++ }}</span>
                                <span class="fw-semibold">{{ $do->do_no }}</span>
                            </div>

                            <div class="small mb-2">
                                <div><span class="text-secondary">ลูกค้า:</span> {{ $do->customer_name }}</div>
                                <div><span class="text-secondary">ที่อยู่เต็ม:</span> {{ $do->address }}</div>
                                <div><span class="text-secondary">จังหวัด:</span> {{ $do->province }}</div>
                                <div><span class="text-secondary">คลัง:</span> {{ $do->warehouse_code }}</div>
                                <div><span class="text-secondary">กำหนดรับ:</span> {{ $do->inv_date }}</div>
                                <div><span class="text-secondary">กำหนดส่ง:</span> {{ $do->delivery_date }}</div>
                            </div>

                            {{-- ถ้าต้องการรายละเอียดเพิ่ม (ความจุ, ที่อยู่เต็ม ฯลฯ) --}}
                            <button class="btn btn-sm btn-outline-secondary w-100"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#do-detail-{{ $do->id }}">
                                รายละเอียดเพิ่ม
                            </button>

                            <div class="collapse mt-2" id="do-detail-{{ $do->id }}">
                                <div class="small text-secondary">
                                    <span>รายการสินค้า:</span><br>
                                    @foreach($do->items as $item)
                                        • {{ $item->brand }} {{ $item->model }}
                                        ({{ $item->qty }})<br>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>

    {{-- Accept Button --}}
    <div class="d-flex justify-content-end gap-3">
        <a href="{{ route('driver.jobs') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> ย้อนกลับ
        </a>

        <a href="{{ route('driver.jobs.accept', $job->id) }}" class="btn btn-warning">
            รับงานนี้
        </a>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
