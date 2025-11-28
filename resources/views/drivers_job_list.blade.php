<!DOCTYPE html>
<html lang="th" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>งานที่รอให้คนขับรับ</title>

    <link rel="icon" type="image/png" href="/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/driver_register.css'])
</head>
<body class="py-4">

<div class="container-full mb-4">
    <div class="d-flex justify-content-between align-items-end flex-wrap gap-2">
        <div>
            <h1 class="display-6 fw-bold mb-1">
                เลือกรับ<span class="text-primary">งานขนส่ง</span>
            </h1>
            <p class="text-secondary mb-0">แตะเลือกงานที่ต้องการวิ่งได้เลย (mockup)</p>
        </div>
        <span class="badge">
            <span class="d-inline-block rounded-circle bg-success p-1 me-1"></span>
            System Online
        </span>
    </div>
</div>

<div class="container-full">
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">

        @forelse($jobs as $job)
            @php
                $provinces = $job->delivery_provinces;
                $brandSummary = $job->brand_summary ?? [];
            @endphp

            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body p-3 p-md-4">

                        <div class="d-flex justify-content-between mb-3" style="font-size: 1.25rem">
                            <div>
                                <span class="d-block">เลขที่งาน: {{ $job->job_number }}</span>
                            </div>
                            <div class="text-end">
                                <span class="fw-semibold">{{ $job->truck_type }}</span>
                            </div>
                        </div>

                        <div class="card-mini p-3 pb-0 pt-0 mb-2">
                            <p class="mb-1 mt-2">
                                เวลารับ {{ $job->receive_date }}
                            </p>
                            <p class="mb-2">
                                สถานที่รับ :
                                <a href="{{ route('driver.jobs.show', $job->id) }}" class="text-decoration-underline">
                                    {{ $job->receive_location }}
                                </a>
                            </p>
                        </div>
                        
                        <div class="card-mini p-3 pb-0 pt-0 mb-3">
                            <p class="mb-1 mt-2">
                                เวลาส่ง {{ $job->delivery_date }}
                            </p>
                            <p class="mb-2">
                                สถานที่ส่ง : {{ $provinces }}
                            </p>
                        </div>

                        <p class="mb-2 ms-4">
                            จำนวนชิ้น :
                            {{ $job->total_qty }}
                        </p>
                        <p class="mb-0 ms-4">
                            น้ำหนักรวม :
                            {{ $job->total_weight }}
                        </p>

                        <hr class="text-secondary my-3">

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-semibold">รายละเอียดเพิ่มเติม</span>
                            <button class="btn btn-sm border-0 bg-transparent"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#job-extra-{{ $job->id }}">
                                ▼ คลิกเพื่อดูเพิ่มเติม
                            </button>
                        </div>

                        <div class="collapse" id="job-extra-{{ $job->id }}">
                            <div class="pt-2">

                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-2 fw-semibold">
                                            ลูกค้าที่ต้องส่งให้
                                        </div>
                                        <table class="table table-sm brand-table small align-middle">
                                            <thead>
                                                <tr>
                                                    <th>Brand</th>
                                                    <th class="text-end">จำนวน DO</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($brandSummary as $row)
                                                    <tr>
                                                        <td>{{ $row['brand'] }}</td>
                                                        <td class="text-end">{{ $row['do_count'] }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="2" class="text-secondary">ยังไม่มีข้อมูลสรุป brand</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>

                                        <div class="col-12 mt-2">
                                            <div class="fw-semibold mb-1">เส้นทางคร่าว ๆ:</div>
                                            <span class="small">{{ $job->rough_route }}</span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>


                        <div class="d-flex justify-content-end gap-2 mt-3">
                            <a href="{{ route('driver.jobs.show', $job->id) }}"
                               class="btn btn-outline-secondary btn-sm d-flex justify-content-center align-items-center">
                                ดูรายละเอียดงาน
                            </a>
                            <a href="{{ route('driver.jobs.accept', $job->id) }}"
                               class="btn btn-warning btn-sm d-flex justify-content-center align-items-center">
                                รับงานนี้
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-emoji-neutral" style="font-size:3rem;"></i>
                <p class="mt-2 text-secondary mb-0">ยังไม่มีงาน (mock)</p>
            </div>
        @endforelse

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
