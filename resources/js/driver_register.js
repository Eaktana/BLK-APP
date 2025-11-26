// ส่วนที่ 1: จัดการการเลือกที่อยู่ (ไม่เกี่ยวข้องกับการแก้ไขปุ่มลบ)
document.addEventListener('DOMContentLoaded', function() {
    const provinceSelect = document.getElementById('province');
    const amphureSelect = document.getElementById('amphure');
    const tambonSelect = document.getElementById('tambon');
    const zipcodeInput = document.getElementById('zipcode');

    let allData = [];

    // ดึงข้อมูลจาก GitHub
    fetch('https://raw.githubusercontent.com/kongvut/thai-province-data/master/api/latest/province_with_district_and_sub_district.json')
        .then(response => response.json())
        .then(data => {
            allData = data;
            populateProvinces();
        })
        .catch(error => {
            console.error('Address API Error:', error);
            if(provinceSelect) provinceSelect.innerHTML = '<option value="" disabled>โหลดข้อมูลไม่สำเร็จ</option>';
        });

    function populateProvinces() {
        if(!provinceSelect) return;
        provinceSelect.innerHTML = '<option value="" selected disabled>เลือกจังหวัด...</option>';
        allData.sort((a, b) => a.name_th.localeCompare(b.name_th));
        allData.forEach(province => {
            let option = document.createElement('option');
            option.value = province.name_th;
            option.textContent = province.name_th;
            provinceSelect.appendChild(option);
        });
    }

    // Event: เลือกจังหวัด
    if(provinceSelect) {
        provinceSelect.addEventListener('change', function() {
            amphureSelect.innerHTML = '<option value="" selected disabled>เลือกอำเภอ...</option>';
            tambonSelect.innerHTML = '<option value="" selected disabled>เลือกตำบล...</option>';
            zipcodeInput.value = '';

            const selectedProvince = allData.find(p => p.name_th === this.value);
            if (selectedProvince && selectedProvince.districts) {
                selectedProvince.districts.sort((a, b) => a.name_th.localeCompare(b.name_th));
                selectedProvince.districts.forEach(district => {
                    let option = document.createElement('option');
                    option.value = district.name_th;
                    option.textContent = district.name_th;
                    amphureSelect.appendChild(option);
                });
            }
        });
    }

    // Event: เลือกอำเภอ
    if(amphureSelect) {
        amphureSelect.addEventListener('change', function() {
            tambonSelect.innerHTML = '<option value="" selected disabled>เลือกตำบล...</option>';
            zipcodeInput.value = '';

            const selectedProvince = allData.find(p => p.name_th === provinceSelect.value);
            const selectedDistrict = selectedProvince.districts.find(d => d.name_th === this.value);

            if (selectedDistrict && selectedDistrict.sub_districts) {
                selectedDistrict.sub_districts.sort((a, b) => a.name_th.localeCompare(b.name_th));
                selectedDistrict.sub_districts.forEach(sub => {
                    let option = document.createElement('option');
                    option.value = sub.name_th;
                    option.textContent = sub.name_th;
                    option.dataset.zipcode = sub.zip_code;
                    tambonSelect.appendChild(option);
                });
            }
        });
    }

    // Event: เลือกตำบล -> ใส่รหัสไปรษณีย์
    if(tambonSelect) {
        tambonSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.dataset.zipcode) {
                zipcodeInput.value = selectedOption.dataset.zipcode;
            }
        });
    }
});


// =========================================================
// ส่วนที่ 2: จัดการผู้ช่วย (Helpers)
// =========================================================

// ทำให้ฟังก์ชันเหล่านี้เรียกใช้ได้จาก HTML (onclick/onchange)
window.addHelper = function() {
    const container = document.getElementById('helpers-container');
    const newIndex = container.querySelectorAll('.helper-item').length;

    // ************************************************
    // แก้ไข: เปลี่ยนไปใช้โครงสร้าง HTML ที่มีการจัดวางปุ่มลบแบบไม่ซ่อน (d-flex)
    // ************************************************
    const html = `
        <div class="helper-item ps-3 border-start border-3 border-primary mb-3" data-index="${newIndex}">
            
            <!-- START: หัวข้อและปุ่มลบ (แสดงตลอดเวลา) -->
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="text-uppercase text-secondary small fw-bold mb-0 helper-title">ผู้ช่วยคนที่ ${newIndex + 1}</h6>
                <div class="d-flex justify-content-between align-items-center mb-4 border-secondary">
                    <button type="button" onclick="removeHelper(this)" class="btn btn-sm btn-danger">
                        <i class="bi bi-trash-fill fs-7"></i> ลบคน
                    </button>
                </div>
            </div>
            <!-- END: หัวข้อและปุ่มลบ -->

            <div class="row g-2">
                <div class="col-md-5">
                    <div class="input-group">
                        <select name="helpers[${newIndex}][prefix]" class="form-select" style="max-width: 80px;" onchange="toggleHelperInputs(this)">
                            <option value="นาย">นาย</option>
                            <option value="นาง">นาง</option>
                            <option value="นางสาว">นางสาว</option>
                        </select>
                        <input type="text" name="helpers[${newIndex}][name]" class="form-control helper-name-input" placeholder="ชื่อ-สกุล" oninput="toggleHelperInputs(this)">
                    </div>
                </div>
                <div class="col-md-3">
                    <input type="text" name="helpers[${newIndex}][phone]" class="form-control helper-required-input" placeholder="เบอร์โทร" disabled>
                </div>
                <div class="col-md-4">
                    <!-- ใช้ input file แบบเดิม -->
                    <input type="file" name="helpers[${newIndex}][idcard]" class="form-control form-control-sm helper-required-input" accept="image/*" disabled>
                </div>
                <div class="col-12">
                    <input type="text" name="helpers[${newIndex}][info]" class="form-control helper-info-input" placeholder="ข้อมูลเพิ่มเติม" disabled>
                </div>
            </div>
            <!-- ปุ่มลบเดิมที่ถูกลบออกไปแล้ว -->
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
    reorderHelpers();
};

window.removeHelper = function(button) {
    const item        = button.closest('.helper-item');
    const idInput     = item.querySelector('input[name*="[id]"]');
    const deleteInput = item.querySelector('.helper-delete-flag');

    // ถ้ามี id = helper ที่อยู่ใน DB → mark ว่าจะลบ + ซ่อน
    if (idInput && idInput.value) {
        if (deleteInput) {
            deleteInput.value = 1;        // 👉 ส่ง _delete = 1 ไปให้ controller
        }
        item.classList.add('d-none');      // ซ่อนจากหน้าจอ แต่ยัง submit form
        // ไม่ต้อง reorderHelpers() เพราะเรายังอยากให้ index เดิมอยู่
    } else {
        // ถ้าไม่มี id = helper ใหม่ที่ยังไม่ได้บันทึก → ลบทิ้งจาก DOM ได้เลย
        item.remove();
        if (typeof reorderHelpers === 'function') {
            reorderHelpers();
        }
    }
};


window.toggleHelperInputs = function(element) {
    const item = element.closest('.helper-item');
    const nameInput = item.querySelector('.helper-name-input');
    
    // ถ้ามีชื่อ -> เปิดใช้งานช่องอื่นๆ
    const hasName = nameInput.value.trim() !== "";
    
    const inputs = item.querySelectorAll('.helper-required-input, .helper-info-input');
    const fileDisplayNameInput = item.querySelector('.helper-file-name-display');

    inputs.forEach(input => {
        input.disabled = !hasName;
    });
    
    // สลับสถานะของช่องแสดงชื่อไฟล์
    if (fileDisplayNameInput) {
        fileDisplayNameInput.disabled = !hasName;
        if (!hasName) fileDisplayNameInput.value = ''; // เคลียร์ค่าเมื่อปิดใช้งาน
    }
    
    // ลบส่วนที่จัดการแสดง/ซ่อนปุ่มถังขยะออก
};

function reorderHelpers() {
    const items = document.querySelectorAll('.helper-item');
    items.forEach((item, index) => {
        item.dataset.index = index;
        // อัปเดตหัวข้อ
        const title = item.querySelector('.helper-title');
        if(title) title.innerText = `ผู้ช่วยคนที่ ${index + 1}`;

        // อัปเดต name[...] ของ input
        item.querySelectorAll('input, select').forEach(input => {
            const name = input.getAttribute('name');
            if(name) {
                const newName = name.replace(/helpers\[\d+\]/, `helpers[${index}]`);
                input.setAttribute('name', newName);
            }
        });
    });
}

// =========================================================
// ส่วนที่ 3: จัดการผู้ติดต่อฉุกเฉิน (Emergency)
// =========================================================

window.handleEmergencyState = function() {
    const nameInput = document.getElementById('emergency_name');
    const relInput = document.getElementById('emergency_relationship');
    const phoneInput = document.getElementById('emergency_phone');

    if(nameInput) {
        const hasName = nameInput.value.trim() !== "";
        
        [relInput, phoneInput].forEach(input => {
            if(input) {
                input.disabled = !hasName;
                input.required = hasName;
                if(!hasName) input.value = '';
            }
        });
    }
};

// =========================================================
// ส่วนที่ 4: Init (เริ่มทำงานเมื่อโหลดเสร็จ)
// =========================================================
document.addEventListener('DOMContentLoaded', function() {
    // ตรวจสอบ Helper คนแรก (ถ้ามีชื่อค้างอยู่ ให้เปิดใช้งาน input)
    const firstHelperName = document.querySelector('.helper-item[data-index="0"] .helper-name-input');
    if(firstHelperName) window.toggleHelperInputs(firstHelperName);

    // ตรวจสอบ Emergency Contact
    window.handleEmergencyState();
});

document.addEventListener('DOMContentLoaded', function() {
    const provinceSelect = document.getElementById('province');
    const amphureSelect  = document.getElementById('amphure');
    const tambonSelect   = document.getElementById('tambon');
    const zipcodeInput   = document.getElementById('zipcode');

    let allData = [];

    // ค่าเดิมจากหน้า edit (ถ้าเป็นหน้า register จะเป็นค่าว่าง)
    const currentProvince = provinceSelect ? provinceSelect.dataset.current || '' : '';
    const currentDistrict = amphureSelect ? amphureSelect.dataset.current || '' : '';
    const currentTambon   = tambonSelect ? tambonSelect.dataset.current || '' : '';

    fetch('https://raw.githubusercontent.com/kongvut/thai-province-data/master/api/latest/province_with_district_and_sub_district.json')
        .then(response => response.json())
        .then(data => {
            allData = data;
            populateProvinces();
        })
        .catch(error => {
            console.error('Address API Error:', error);
            if (provinceSelect) {
                provinceSelect.innerHTML = '<option value="" disabled>โหลดข้อมูลไม่สำเร็จ</option>';
            }
        });

    function populateProvinces() {
        if (!provinceSelect) return;

        provinceSelect.innerHTML = '<option value="" disabled>เลือกจังหวัด...</option>';

        allData.sort((a, b) => a.name_th.localeCompare(b.name_th));
        allData.forEach(province => {
            const option = document.createElement('option');
            option.value = province.name_th;
            option.textContent = province.name_th;
            provinceSelect.appendChild(option);
        });

        // ถ้ามีค่าเดิม (หน้า edit) -> set แล้ว trigger change ให้ไปโหลดอำเภอ
        if (currentProvince) {
            provinceSelect.value = currentProvince;
            provinceSelect.dispatchEvent(new Event('change'));
        }
    }

    // เลือกจังหวัด
    if (provinceSelect) {
        provinceSelect.addEventListener('change', function() {
            amphureSelect.innerHTML = '<option value="" selected disabled>เลือกอำเภอ...</option>';
            tambonSelect.innerHTML = '<option value="" selected disabled>เลือกตำบล...</option>';
            zipcodeInput.value = '';

            const selectedProvince = allData.find(p => p.name_th === this.value);
            if (selectedProvince && selectedProvince.districts) {
                selectedProvince.districts.sort((a, b) => a.name_th.localeCompare(b.name_th));
                selectedProvince.districts.forEach(district => {
                    const option = document.createElement('option');
                    option.value = district.name_th;
                    option.textContent = district.name_th;
                    amphureSelect.appendChild(option);
                });

                // หน้า edit: set อำเภอเดิม + trigger change
                if (currentDistrict && this.value === currentProvince) {
                    amphureSelect.value = currentDistrict;
                    amphureSelect.dispatchEvent(new Event('change'));
                }
            }
        });
    }

    // เลือกอำเภอ
    if (amphureSelect) {
        amphureSelect.addEventListener('change', function() {
            tambonSelect.innerHTML = '<option value="" selected disabled>เลือกตำบล...</option>';
            zipcodeInput.value = '';

            const selectedProvince = allData.find(p => p.name_th === provinceSelect.value);
            const selectedDistrict = selectedProvince?.districts.find(d => d.name_th === this.value);

            if (selectedDistrict && selectedDistrict.sub_districts) {
                selectedDistrict.sub_districts.sort((a, b) => a.name_th.localeCompare(b.name_th));
                selectedDistrict.sub_districts.forEach(sub => {
                    const option = document.createElement('option');
                    option.value = sub.name_th;
                    option.textContent = sub.name_th;
                    option.dataset.zipcode = sub.zip_code;
                    tambonSelect.appendChild(option);
                });

                // หน้า edit: set ตำบลเดิม + trigger change ให้เติม zipcode
                if (currentTambon && this.value === currentDistrict) {
                    tambonSelect.value = currentTambon;
                    tambonSelect.dispatchEvent(new Event('change'));
                }
            }
        });
    }

    // เลือกตำบล
    if (tambonSelect) {
        tambonSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.dataset.zipcode) {
                zipcodeInput.value = selectedOption.dataset.zipcode;
            }
        });
    }
});
