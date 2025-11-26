<!DOCTYPE html>
<html lang="th" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ลงทะเบียนพนักงาน</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- ถ้ายังต้องการใช้ JS เดิม -->
    @vite(['resources/css/driver_register.css', 'resources/js/driver_register.js'])
</head>
<body class="py-5">

    <!-- Background Effects -->
    <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -1; overflow: hidden; pointer-events: none;">
        <div style="position: absolute; top: -10%; left: 20%; width: 500px; height: 500px; background: radial-gradient(circle, rgba(59,130,246,0.15) 0%, rgba(0,0,0,0) 70%);"></div>
        <div style="position: absolute; bottom: -10%; right: 20%; width: 500px; height: 500px; background: radial-gradient(circle, rgba(168,85,247,0.15) 0%, rgba(0,0,0,0) 70%);"></div>
    </div>

    <div class="container">
        
        <!-- Header -->
        <div class="row align-items-end mb-5 text-center text-md-start">
            <div class="col-md-8">
                <h1 class="display-6 fw-bold mb-2">
                    ลงทะเบียน<span class="text-primary">พนักงานขับรถ</span>
                </h1>
                <p class="text-secondary mb-0">กรอกข้อมูลเพื่อสร้างโปรไฟล์ผู้ขับขี่ใหม่ในระบบ</p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <span class="badge bg-dark border border-secondary p-2 text-secondary">
                    <span class="d-inline-block rounded-circle bg-success p-1 me-1 align-middle"></span>
                    System Online
                </span>
            </div>
        </div>

        <form action="{{ route('register.save') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Card: ข้อมูลส่วนตัว -->
            <div class="card mb-4 shadow-lg">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4 pb-3 border-bottom border-secondary">
                        <div class="me-3 text-primary">
                            <i class="bi bi-person-vcard fs-4"></i>
                        </div>
                        <h4 class="card-title mb-0">ข้อมูลส่วนตัว</h4>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">ชื่อ-นามสกุล <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select name="prefix" class="form-select" style="max-width: 100px;">
                                    <option value="นาย">นาย</option>
                                    <option value="นาง">นาง</option>
                                    <option value="นางสาว">น.ส.</option>
                                </select>
                                <input type="text" name="first_last_name" class="form-control" placeholder="ระบุชื่อและนามสกุล" value="{{ old('first_last_name') }}">
                            </div>
                            @error('first_last_name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">เบอร์โทรศัพท์ <span class="text-danger">*</span></label>
                            <input type="text" name="driver_phone" class="form-control" placeholder="08x-xxx-xxxx" value="{{ old('driver_phone') }}">
                            @error('driver_phone') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-12"><hr class="text-secondary my-2"></div>

                        <div class="col-12">
                            <label class="form-label">ที่อยู่ (บ้านเลขที่, หมู่, อาคาร)</label>
                            <input type="text" name="address_details" class="form-control" value="{{ old('address_details') }}">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">ซอย</label>
                            <input type="text" name="soi" class="form-control" value="{{ old('soi') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">ถนน</label>
                            <input type="text" name="road" class="form-control" value="{{ old('road') }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">จังหวัด <span class="text-danger">*</span></label>
                            <select name="province" id="province" class="form-select"><option value="" disabled selected>กำลังโหลด...</option></select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">อำเภอ/เขต <span class="text-danger">*</span></label>
                            <select name="district" id="amphure" class="form-select"><option value="" disabled selected>เลือกอำเภอ...</option></select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">ตำบล/แขวง <span class="text-danger">*</span></label>
                            <select name="sub_district" id="tambon" class="form-select"><option value="" disabled selected>เลือกตำบล...</option></select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">รหัสไปรษณีย์</label>
                            <input type="text" name="zipcode" id="zipcode" class="form-control bg-dark text-muted" readonly value="{{ old('zipcode') }}">
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
                                <i class="bi bi-file-earmark-richtext text-primary me-2"></i> เอกสารประกอบ
                            </h5>
                            <div class="d-flex flex-column gap-3">
                                @foreach(['doc_license'=>'ใบขับขี่', 'doc_face'=>'รูปหน้าตรง', 'doc_idcard'=>'บัตรประชาชน', 'doc_car_registration'=>'สำเนาทะเบียนรถ'] as $field => $label)
                                <div>
                                    <label class="form-label small text-secondary mb-1">{{ $label }}</label>
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
                                <i class="bi bi-truck text-primary me-2"></i> สภาพรถ & ความปลอดภัย
                            </h5>
                            
                                <label class="text-uppercase fw-bold text-secondary small mb-3 d-block">รูปถ่ายรถ 4 ด้าน</label>
                                <div class="row g-3 mb-4">
                                    <!-- START: Updated structure for car photo uploads -->
                                    <div class="col-md-3 col-6">
                                        <!-- Image Preview/Placeholder Area -->
                                        <div class="image-preview-box" id="preview_car_photo_front">
                                            <!-- Placeholder Image: Simple front view (using placeholder service) -->
                                            <img src="{{ Vite::asset('resources/assets/images/img_truck_front.png') }}" alt="ตัวอย่างรูปด้านหน้า" class="sample-image" style="cursor:pointer;"  onclick="window.open(this.src, '_blank')">
                                        </div>
                                        
                                        <label class="form-label small text-secondary mb-1">ด้านหน้า</label>
                                        <!-- Upload Input -->
                                        <input type="file" name="car_photo_front" class="form-control form-control-sm image-upload-input" accept="image/*" onchange="previewImage(this, 'preview_car_photo_front')">
                                    </div>

                                    <div class="col-md-3 col-6">
                                        <div class="image-preview-box" id="preview_car_photo_back">
                                            <!-- Placeholder Image: Simple back view -->
                                            <img src="{{ Vite::asset('resources/assets/images/img_truck_rear.png') }}" alt="ตัวอย่างรูปด้านหลัง" class="sample-image" style="cursor:pointer;"  onclick="window.open(this.src, '_blank')">
                                        </div>
                                        <label class="form-label small text-secondary mb-1">ด้านหลัง</label>
                                        <input type="file" name="car_photo_back" class="form-control form-control-sm image-upload-input" accept="image/*" onchange="previewImage(this, 'preview_car_photo_back')">
                                    </div>
                                    
                                    <div class="col-md-3 col-6">
                                        <div class="image-preview-box" id="preview_car_photo_left">
                                            <!-- Placeholder Image: Simple side view (left) -->
                                            <img src="{{ Vite::asset('resources/assets/images/img_truck_left.png') }}" alt="ตัวอย่างรูปด้านซ้าย" class="sample-image" style="cursor:pointer;"  onclick="window.open(this.src, '_blank')">
                                        </div>
                                        <label class="form-label small text-secondary mb-1">ด้านซ้าย</label>
                                        <input type="file" name="car_photo_left" class="form-control form-control-sm image-upload-input" accept="image/*" onchange="previewImage(this, 'preview_car_photo_left')">
                                    </div>
                                    
                                    <div class="col-md-3 col-6">
                                        <div class="image-preview-box" id="preview_car_photo_right">
                                            <!-- Placeholder Image: Simple side view (right) -->
                                            <img src="{{ Vite::asset('resources/assets/images/img_truck_right.png') }}" alt="ตัวอย่างรูปด้านขวา" class="sample-image" style="cursor:pointer;"  onclick="window.open(this.src, '_blank')">
                                        </div>
                                        <label class="form-label small text-secondary mb-1">ด้านขวา</label>
                                        <input type="file" name="car_photo_right" class="form-control form-control-sm image-upload-input" accept="image/*" onchange="previewImage(this, 'preview_car_photo_right')">
                                    </div>
                                    <!-- END: Updated structure for car photo uploads -->
                                </div>

                                <label class="text-uppercase fw-bold text-secondary small mb-3 d-block">อุปกรณ์เซฟตี้</label>
                                <div class="d-flex flex-column gap-3">
                                    @php
                                        $ppeItems = [
                                            'safety_vest'        => 'เสื้อสะท้อนแสง',
                                            'safety_shoes'       => 'รองเท้าหัวเหล็ก',
                                            'safety_wheel_chock' => 'หมอนรองล้อ',
                                        ];
                                        @endphp

                                    @foreach($ppeItems as $field => $label)
                                        <div>
                                            <label class="form-label small text-secondary mb-1">{{ $label }}</label>
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
                                    <i class="bi bi-people-fill text-primary me-2"></i> ผู้ช่วยคนขับ
                                </h5>
                                <button type="button" onclick="addHelper()" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-lg"></i> เพิ่มคน
                                </button>
                            </div>
                            <div id="helpers-container">
                                <!-- Helper Item 1 -->
                                <div class="helper-item position-relative ps-3 border-start border-3 border-primary mb-3" data-index="0">
                                    <label class="text-uppercase text-secondary small fw-bold mb-2 helper-title">ผู้ช่วยคนที่ 1</label>
                                    <div class="row g-2">
                                        <div class="col-md-5">
                                            <div class="input-group">
                                                <select name="helpers[0][prefix]" class="form-select" style="max-width: 80px;" onchange="toggleHelperInputs(this)">
                                                    <option value="นาย">นาย</option>
                                                    <option value="นาง">นาง</option>
                                                    <option value="นางสาว">นางสาว</option>
                                                </select>
                                                <input type="text" name="helpers[0][name]" class="form-control helper-name-input" placeholder="ชื่อ-สกุล" oninput="toggleHelperInputs(this)">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" name="helpers[0][phone]" class="form-control helper-required-input" placeholder="เบอร์โทร" disabled>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="file" name="helpers[0][idcard]" class="form-control form-control-sm helper-required-input" accept="image/*" disabled>
                                        </div>
                                        <div class="col-12">
                                            <input type="text" name="helpers[0][info]" class="form-control helper-info-input" placeholder="ข้อมูลเพิ่มเติม" disabled>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-link text-danger btn-sm position-absolute top-0 end-0 p-0 text-decoration-none d-none" onclick="removeHelper(this)"><i class="bi bi-trash-fill"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card: ติดต่อฉุกเฉิน -->
                <div class="col-lg-12">
                    <div class="card shadow-lg">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4 d-flex align-items-center">
                                <i class="bi bi-exclamation-triangle-fill text-primary me-2 me-2"></i> ติดต่อฉุกเฉิน
                            </h5>
                            <div class="d-flex flex-column gap-3">
                                <div>
                                    <label class="form-label small fw-bold text-uppercase">ชื่อผู้ติดต่อ</label>
                                    <div class="input-group">
                                        <select name="emergency_prefix" class="form-select" style="max-width: 80px;">
                                            <option value="นาย">นาย</option>
                                            <option value="นาง">นาง</option>
                                            <option value="นางสาว">นางสาว</option>
                                        </select>
                                        <input type="text" name="emergency_name" id="emergency_name" class="form-control" placeholder="ชื่อ-สกุล" oninput="handleEmergencyState()">
                                    </div>
                                </div>
                                <input type="text" name="emergency_relationship" id="emergency_relationship" class="form-control" placeholder="ความสัมพันธ์" disabled>
                                <input type="text" name="emergency_phone" id="emergency_phone" class="form-control" placeholder="เบอร์โทร" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-end gap-2 mt-5  pt-3 border-top border-secondary">
                <a href="/" class="btn btn-outline-secondary px-4">
                    ยกเลิก
                </a>
                <button type="submit" id="submitBtn" class="btn btn-primary px-4 shadow">
                    <i class="me-2"></i> ยืนยันการลงทะเบียน
                </button>
            </div>

        </form>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>