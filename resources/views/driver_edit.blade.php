<!DOCTYPE html>
<html lang="th" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลพนักงานขับรถ</title>
    <link rel="icon" type="image/png" href="/favicon.png">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/driver_register.css', 'resources/js/driver_register.js'])
</head>
<body class="py-5">

@php
    // แยกคำนำหน้า/ชื่อ จาก driver_name เดิม
    $nameParts = explode(' ', $driver->driver_name, 2);
    $currentPrefix = $nameParts[0] ?? '';
    $currentName   = $nameParts[1] ?? $driver->driver_name;

    // แยก emergency contact
    $ecParts   = explode(' ', $driver->ec_name ?? '', 2);
    $ecPrefix  = $ecParts[0] ?? '';
    $ecName    = $ecParts[1] ?? '';

    $helpers = $driver->helpers ?? collect();
@endphp

    <!-- Background Effects -->
    <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -1; overflow: hidden; pointer-events: none;">
        <div style="position: absolute; top: -10%; left: 20%; width: 500px; height: 500px; background: radial-gradient(circle, rgba(250,204,21,0.15) 0%, rgba(0,0,0,0) 70%);"></div>
        <div style="position: absolute; bottom: -10%; right: 20%; width: 500px; height: 500px; background: radial-gradient(circle, rgba(234,179,8,0.15) 0%, rgba(0,0,0,0) 70%);"></div>
    </div>

    <div class="container">
        
        <!-- Header -->
        <div class="row align-items-end mb-5 text-center text-md-start">
            <div class="col-md-8">
                <h1 class="display-6 fw-bold mb-2">
                    แก้ไขข้อมูล<span class="text-warning">พนักงานขับรถ</span>
                </h1>
                <p class="text-secondary mb-0">ปรับปรุงข้อมูลผู้ขับขี่ในระบบ</p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <span class="badge bg-dark border border-secondary p-2 text-secondary">
                    <span class="d-inline-block rounded-circle bg-success p-1 me-1 align-middle"></span>
                    System Online
                </span>
            </div>
        </div>

        <form action="{{ route('drivers.update', $driver->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Card: ข้อมูลส่วนตัว -->
            <div class="card mb-4 shadow-lg">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4 pb-3 border-bottom border-secondary">
                        <div class="me-2 text-warning">
                            <i class="bi bi-person-vcard fs-4"></i>
                        </div>
                        <h4 class="card-title mb-0">ข้อมูลส่วนตัว</h4>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">ชื่อ-นามสกุล <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select name="prefix" class="form-select" style="max-width: 100px;">
                                    <option value="นาย"    {{ old('prefix', $currentPrefix) === 'นาย' ? 'selected' : '' }}>นาย</option>
                                    <option value="นาง"    {{ old('prefix', $currentPrefix) === 'นาง' ? 'selected' : '' }}>นาง</option>
                                    <option value="นางสาว" {{ old('prefix', $currentPrefix) === 'นางสาว' ? 'selected' : '' }}>น.ส.</option>
                                </select>
                                <input type="text" name="first_last_name" class="form-control"
                                       placeholder="ระบุชื่อและนามสกุล"
                                       value="{{ old('first_last_name', $currentName) }}">
                            </div>
                            @error('first_last_name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">เบอร์โทรศัพท์ <span class="text-danger">*</span></label>
                            <input type="text" name="driver_phone" class="form-control"
                                   value="{{ old('driver_phone', $driver->driver_phone) }}">
                            @error('driver_phone') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-12"><hr class="text-secondary my-2"></div>

                        <div class="col-12">
                            <label class="form-label">ที่อยู่ (บ้านเลขที่, หมู่, อาคาร)</label>
                            <input type="text" name="address_details" class="form-control"
                                   value="{{ old('address_details', $driver->address_line) }}">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">ซอย</label>
                            <input type="text" name="soi" class="form-control"
                                   value="{{ old('soi', $driver->soi) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">ถนน</label>
                            <input type="text" name="road" class="form-control"
                                   value="{{ old('road', $driver->street) }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">จังหวัด <span class="text-danger">*</span></label>
                            <select name="province" id="province"
                                    class="form-select"
                                    data-current="{{ old('province', $driver->province) }}">
                                <option value="" disabled selected>กำลังโหลด...</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">อำเภอ/เขต <span class="text-danger">*</span></label>
                            <select name="district" id="amphure"
                                    class="form-select"
                                    data-current="{{ old('district', $driver->district) }}">
                                <option value="" disabled selected>เลือกอำเภอ...</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">ตำบล/แขวง <span class="text-danger">*</span></label>
                            <select name="sub_district" id="tambon"
                                    class="form-select"
                                    data-current="{{ old('sub_district', $driver->sub_district) }}">
                                <option value="" disabled selected>เลือกตำบล...</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">รหัสไปรษณีย์</label>
                            <input type="text" name="zipcode" id="zipcode"
                                class="form-control bg-dark text-muted"
                                value="{{ old('zipcode', $driver->postal_code) }}"
                                readonly>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Card: เอกสารประกอบ -->
                <div class="col-lg-12">
                    <div class="card h-100 shadow-lg">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4 d-flex align-items-center">
                                <i class="bi bi-file-earmark-richtext text-warning me-2"></i> เอกสารประกอบ
                            </h5>
                            <div class="d-flex flex-column gap-3">
                                @php
                                    $docMap = [
                                        'file_driving_license' => 'ใบขับขี่',
                                        'file_profile_photo'   => 'รูปหน้าตรง',
                                        'file_id_card'         => 'บัตรประชาชน',
                                        'file_vehicle_reg'     => 'สำเนาทะเบียนรถ',
                                    ];
                                @endphp

                                @foreach($docMap as $field => $label)
                                    <div>
                                        <label class="form-label small text-secondary mb-1">{{ $label }}</label><br>

                                        @if($driver->$field)
                                            <img src="{{ asset('storage/'.$driver->$field) }}" 
                                                alt="{{ $label }}"
                                                class="rounded mb-2"
                                                style="max-height: 80px; cursor: pointer;"
                                                onclick="window.open('{{ asset('storage/'.$driver->$field) }}', '_blank')">
                                        @endif

                                        <input type="file" name="{{ $field }}" class="form-control form-control-sm" accept="image/*">
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card: สภาพรถ & ความปลอดภัย -->
                <div class="col-lg-12">
                    <div class="card h-100 shadow-lg">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4 d-flex align-items-center">
                                <i class="bi bi-truck text-warning me-2"></i> สภาพรถ & ความปลอดภัย
                            </h5>
                            
                            <label class="text-uppercase fw-bold text-secondary small mb-3 d-block">รูปถ่ายรถ 4 ด้าน</label>
                            <div class="row g-3 mb-4">
                                @php
                                    $truckFields = [
                                        'img_truck_front' => ['label' => 'ด้านหน้า', 'placeholder' => 'img_truck_front.png'],
                                        'img_truck_rear'  => ['label' => 'ด้านหลัง', 'placeholder' => 'img_truck_rear.png'],
                                        'img_truck_left'  => ['label' => 'ด้านซ้าย', 'placeholder' => 'img_truck_left.png'],
                                        'img_truck_right' => ['label' => 'ด้านขวา', 'placeholder' => 'img_truck_right.png'],
                                    ];
                                @endphp

                                @foreach($truckFields as $field => $meta)
                                    @php
                                        $hasImage = !empty($driver->$field);
                                        $previewId = 'preview_'.$field;
                                    @endphp

                                    <div class="col-md-3 col-6">

                                        <!-- กรอบ preview -->
                                        <div class="image-preview-box d-flex align-items-center justify-content-center"
                                            id="{{ $previewId }}"
                                            style="height:140px; background:#0f172a; border:1px solid #1e293b; border-radius:8px; overflow:hidden;">

                                            @if($hasImage)
                                                <img src="{{ asset('storage/'.$driver->$field) }}"
                                                    alt="{{ $meta['label'] }}"
                                                    class="w-100 h-100 object-fit-cover"
                                                    style="cursor:pointer;"
                                                    onclick="window.open(this.src, '_blank')">
                                            @else
                                                <span class="text-secondary small">ยังไม่มีรูป</span>
                                            @endif
                                        </div>

                                        <!-- label -->
                                        <label class="form-label small text-secondary mb-1 mt-2">
                                            {{ $meta['label'] }}
                                        </label>

                                        <!-- Upload new file -->
                                        <input type="file"
                                            name="{{ $field }}"
                                            class="form-control form-control-sm image-upload-input"
                                            accept="image/*"
                                            onchange="previewImage(this, '{{ $previewId }}')">
                                    </div>
                                @endforeach

                            </div>

                            <label class="text-uppercase fw-bold text-secondary small mb-3 d-block">อุปกรณ์เซฟตี้</label>
                            <div class="d-flex flex-column gap-3">
                                @php
                                    $ppeItems = [
                                        'img_ppe_vest'     => 'เสื้อสะท้อนแสง',
                                        'img_ppe_shoes'    => 'รองเท้าหัวเหล็ก',
                                        'img_wheel_chock'  => 'หมอนรองล้อ',
                                    ];
                                @endphp

                                @foreach($ppeItems as $field => $label)
                                    <div>
                                        <label class="form-label small text-secondary mb-1">{{ $label }}</label><br>

                                        @if($driver->$field)
                                            <img src="{{ asset('storage/'.$driver->$field) }}"
                                                alt="{{ $label }}"
                                                class="rounded mb-2"
                                                style="max-height: 80px; cursor:pointer;"
                                                onclick="window.open('{{ asset('storage/'.$driver->$field) }}', '_blank')">
                                        @endif

                                        <input type="file" name="{{ $field }}" class="form-control form-control-sm" accept="image/*">
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-1">
                <!-- Card: ผู้ช่วยคนขับ -->
                <div class="col-lg-12">
                    <div class="card shadow-lg">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom border-secondary">
                                <h5 class="card-title mb-4 d-flex align-items-center">
                                    <i class="bi bi-people-fill text-warning me-2"></i> ผู้ช่วยคนขับ
                                </h5>
                                <button type="button" onclick="addHelper()" class="btn btn-sm btn-warning">
                                    <i class="bi bi-plus-lg"></i> เพิ่มคน
                                </button>
                            </div>

                            <div id="helpers-container">
                                @forelse($helpers as $idx => $h)
                                    @php
                                        $hp       = explode(' ', $h->dh_name, 2);
                                        $hpPrefix = $hp[0] ?? '';
                                        $hpName   = $hp[1] ?? $h->dh_name;
                                    @endphp

                                    <div class="helper-item border-start border-3 border-warning mb-3 rounded-3 p-3 position-relative"
                                        data-index="{{ $idx }}">

                                        {{-- ✅ ไว้ให้ controller รู้ว่าเป็น helper เดิม + flag ลบ --}}
                                        <input type="hidden" name="helpers[{{ $idx }}][id]" value="{{ $h->id }}">
                                        <input type="hidden" name="helpers[{{ $idx }}][_delete]" value="0" class="helper-delete-flag">

                                        <!-- ปุ่มลบ -->
                                        <button type="button"
                                                onclick="removeHelper(this)"
                                                class="btn btn-sm btn-danger position-absolute top-0 end-0 mt-2 me-2">
                                            <i class="bi bi-trash-fill"></i> ลบคน
                                        </button>

                                        <div class="mb-3">
                                            <span class="text-uppercase text-secondary small fw-bold helper-title">
                                                ผู้ช่วยคนที่ {{ $idx+1 }}
                                            </span>
                                        </div>

                                        <div class="row g-3 align-items-end">
                                            {{-- ชื่อผู้ช่วย --}}
                                            <div class="col-md-4">
                                                <label class="form-label small text-secondary mb-1">ชื่อผู้ช่วย</label>
                                                <div class="input-group">
                                                    <select name="helpers[{{ $idx }}][prefix]"
                                                            class="form-select form-select-sm"
                                                            style="max-width: 90px;"
                                                            onchange="toggleHelperInputs(this)">
                                                        <option value="นาย"    {{ $hpPrefix === 'นาย' ? 'selected' : '' }}>นาย</option>
                                                        <option value="นาง"    {{ $hpPrefix === 'นาง' ? 'selected' : '' }}>นาง</option>
                                                        <option value="นางสาว" {{ $hpPrefix === 'นางสาว' ? 'selected' : '' }}>นางสาว</option>
                                                    </select>
                                                    <input type="text"
                                                        name="helpers[{{ $idx }}][name]"
                                                        class="form-control form-control-sm helper-name-input"
                                                        placeholder="ชื่อ-สกุล"
                                                        value="{{ $hpName }}"
                                                        oninput="toggleHelperInputs(this)">
                                                </div>
                                            </div>

                                            {{-- เบอร์โทร --}}
                                            <div class="col-md-3">
                                                <label class="form-label small text-secondary mb-1">เบอร์โทร</label>
                                                <input type="text"
                                                    name="helpers[{{ $idx }}][phone]"
                                                    class="form-control form-control-sm helper-required-input"
                                                    placeholder="0x-xxx-xxxx"
                                                    value="{{ $h->dh_phone }}">
                                            </div>

                                            {{-- อัปโหลดรูปบัตร --}}
                                            <div class="col-md-3">
                                                <label class="form-label small text-secondary mb-1">อัปโหลดรูปบัตร</label>
                                                <input type="file"
                                                    name="helpers[{{ $idx }}][idcard]"
                                                    class="form-control form-control-sm helper-required-input"
                                                    accept="image/*">
                                            </div>

                                            {{-- รูปบัตรเดิม / Preview --}}
                                            <div class="col-md-2 text-md-center">
                                                <label class="form-label small text-secondary mb-1 d-block">รูปบัตรเดิม</label>
                                                @if($h->dh_idcard_path)
                                                    <img src="{{ asset('storage/'.$h->dh_idcard_path) }}"
                                                        alt="รูปบัตร"
                                                        class="rounded shadow-sm"
                                                        style="max-height: 70px; cursor:pointer;"
                                                        onclick="window.open('{{ asset('storage/'.$h->dh_idcard_path) }}', '_blank')">
                                                @else
                                                    <span class="text-muted small">ยังไม่มีรูป</span>
                                                @endif
                                            </div>

                                            {{-- ข้อมูลเพิ่มเติม --}}
                                            <div class="col-12">
                                                <label class="form-label small text-secondary mb-1">ข้อมูลเพิ่มเติม</label>
                                                <input type="text"
                                                    name="helpers[{{ $idx }}][info]"
                                                    class="form-control form-control-sm helper-info-input"
                                                    placeholder="เช่น คนยกของ, ขึ้นโรงงาน A, ผ่านอบรม ฯลฯ"
                                                    value="{{ $h->dh_info }}">
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    {{-- ถ้าไม่มี helper เลย ใช้บล็อกเริ่มต้นแบบ layout เดียวกัน --}}
                                    <div class="helper-item border-start border-3 border-warning mb-3 rounded-3 p-3 position-relative" data-index="0">
                                        <button type="button"
                                                onclick="removeHelper(this)"
                                                class="btn btn-sm btn-outline-danger position-absolute top-0 end-0 mt-2 me-2 d-none">
                                            <i class="bi bi-trash-fill"></i> ลบคน
                                        </button>

                                        <div class="mb-3">
                                            <span class="text-uppercase text-secondary small fw-bold helper-title">
                                                ผู้ช่วยคนที่ 1
                                            </span>
                                        </div>

                                        <div class="row g-3 align-items-end">
                                            <div class="col-md-4">
                                                <label class="form-label small text-secondary mb-1">ชื่อผู้ช่วย</label>
                                                <div class="input-group">
                                                    <select name="helpers[0][prefix]"
                                                            class="form-select form-select-sm"
                                                            style="max-width: 90px;"
                                                            onchange="toggleHelperInputs(this)">
                                                        <option value="นาย">นาย</option>
                                                        <option value="นาง">นาง</option>
                                                        <option value="นางสาว">นางสาว</option>
                                                    </select>
                                                    <input type="text"
                                                        name="helpers[0][name]"
                                                        class="form-control form-control-sm helper-name-input"
                                                        placeholder="ชื่อ-สกุล"
                                                        oninput="toggleHelperInputs(this)">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label small text-secondary mb-1">เบอร์โทร</label>
                                                <input type="text"
                                                    name="helpers[0][phone]"
                                                    class="form-control form-control-sm helper-required-input"
                                                    placeholder="0x-xxx-xxxx"
                                                    disabled>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label small text-secondary mb-1">อัปโหลดรูปบัตร</label>
                                                <input type="file"
                                                    name="helpers[0][idcard]"
                                                    class="form-control form-control-sm helper-required-input"
                                                    accept="image/*"
                                                    disabled>
                                            </div>

                                            <div class="col-md-2 text-md-center">
                                                <label class="form-label small text-secondary mb-1 d-block">รูปบัตรเดิม</label>
                                                <span class="text-muted small">ยังไม่มีรูป</span>
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label small text-secondary mb-1">ข้อมูลเพิ่มเติม</label>
                                                <input type="text"
                                                    name="helpers[0][info]"
                                                    class="form-control form-control-sm helper-info-input"
                                                    placeholder="เช่น คนยกของ, ขึ้นโรงงาน A, ผ่านอบรม ฯลฯ"
                                                    disabled>
                                            </div>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card: ติดต่อฉุกเฉิน -->
                <div class="col-lg-12">
                    <div class="card shadow-lg">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4 d-flex align-items-center">
                                <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i> ติดต่อฉุกเฉิน
                            </h5>
                            <div class="d-flex flex-column gap-3">
                                <div>
                                    <label class="form-label small fw-bold text-uppercase">ชื่อผู้ติดต่อ</label>
                                    <div class="input-group">
                                        <select name="emergency_prefix" class="form-select" style="max-width: 80px;">
                                            <option value="นาย"    {{ old('emergency_prefix', $ecPrefix) === 'นาย' ? 'selected' : '' }}>นาย</option>
                                            <option value="นาง"    {{ old('emergency_prefix', $ecPrefix) === 'นาง' ? 'selected' : '' }}>นาง</option>
                                            <option value="นางสาว" {{ old('emergency_prefix', $ecPrefix) === 'นางสาว' ? 'selected' : '' }}>นางสาว</option>
                                        </select>
                                        <input type="text" name="emergency_name" id="emergency_name" class="form-control"
                                               placeholder="ชื่อ-สกุล" value="{{ old('emergency_name', $ecName) }}"
                                               oninput="handleEmergencyState()">
                                    </div>
                                </div>
                                <input type="text" name="emergency_relationship" id="emergency_relationship" class="form-control"
                                       placeholder="ความสัมพันธ์" value="{{ old('emergency_relationship', $driver->ec_relation) }}">
                                <input type="text" name="emergency_phone" id="emergency_phone" class="form-control"
                                       placeholder="เบอร์โทร" value="{{ old('emergency_phone', $driver->ec_phone) }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-end gap-2 mt-5 pt-3 border-top border-secondary">
                <a href="/" class="btn btn-outline-secondary px-4">
                    ยกเลิก
                </a>
                <button type="submit" id="submitBtn" class="btn btn-warning px-4 shadow">
                    <i></i> บันทึกการแก้ไข
                </button>
            </div>

        </form>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
