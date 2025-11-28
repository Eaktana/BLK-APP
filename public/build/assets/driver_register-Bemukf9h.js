document.addEventListener("DOMContentLoaded",function(){const t=document.getElementById("province"),e=document.getElementById("amphure"),n=document.getElementById("tambon"),a=document.getElementById("zipcode");let c=[];fetch("https://raw.githubusercontent.com/kongvut/thai-province-data/master/api/latest/province_with_district_and_sub_district.json").then(o=>o.json()).then(o=>{c=o,p()}).catch(o=>{console.error("Address API Error:",o),t&&(t.innerHTML='<option value="" disabled>โหลดข้อมูลไม่สำเร็จ</option>')});function p(){t&&(t.innerHTML='<option value="" selected disabled>เลือกจังหวัด...</option>',c.sort((o,s)=>o.name_th.localeCompare(s.name_th)),c.forEach(o=>{let s=document.createElement("option");s.value=o.name_th,s.textContent=o.name_th,t.appendChild(s)}))}t&&t.addEventListener("change",function(){e.innerHTML='<option value="" selected disabled>เลือกอำเภอ...</option>',n.innerHTML='<option value="" selected disabled>เลือกตำบล...</option>',a.value="";const o=c.find(s=>s.name_th===this.value);o&&o.districts&&(o.districts.sort((s,l)=>s.name_th.localeCompare(l.name_th)),o.districts.forEach(s=>{let l=document.createElement("option");l.value=s.name_th,l.textContent=s.name_th,e.appendChild(l)}))}),e&&e.addEventListener("change",function(){n.innerHTML='<option value="" selected disabled>เลือกตำบล...</option>',a.value="";const s=c.find(l=>l.name_th===t.value).districts.find(l=>l.name_th===this.value);s&&s.sub_districts&&(s.sub_districts.sort((l,i)=>l.name_th.localeCompare(i.name_th)),s.sub_districts.forEach(l=>{let i=document.createElement("option");i.value=l.name_th,i.textContent=l.name_th,i.dataset.zipcode=l.zip_code,n.appendChild(i)}))}),n&&n.addEventListener("change",function(){const o=this.options[this.selectedIndex];o.dataset.zipcode&&(a.value=o.dataset.zipcode)})});window.addHelper=function(){const t=document.getElementById("helpers-container"),e=t.querySelectorAll(".helper-item").length,n=`
        <div class="helper-item ps-3 border-start border-3 border-primary mb-3" data-index="${e}">
            
            <!-- START: หัวข้อและปุ่มลบ (แสดงตลอดเวลา) -->
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="text-uppercase text-secondary small fw-bold mb-0 helper-title">ผู้ช่วยคนที่ ${e+1}</h6>
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
                        <select name="helpers[${e}][prefix]" class="form-select" style="max-width: 80px;" onchange="toggleHelperInputs(this)">
                            <option value="นาย">นาย</option>
                            <option value="นาง">นาง</option>
                            <option value="นางสาว">นางสาว</option>
                        </select>
                        <input type="text" name="helpers[${e}][name]" class="form-control helper-name-input" placeholder="ชื่อ-สกุล" oninput="toggleHelperInputs(this)">
                    </div>
                </div>
                <div class="col-md-3">
                    <input type="text" name="helpers[${e}][phone]" class="form-control helper-required-input" placeholder="เบอร์โทร" disabled>
                </div>
                <div class="col-md-4">
                    <!-- ใช้ input file แบบเดิม -->
                    <input type="file" name="helpers[${e}][idcard]" class="form-control form-control-sm helper-required-input" accept="image/*" disabled>
                </div>
                <div class="col-12">
                    <input type="text" name="helpers[${e}][info]" class="form-control helper-info-input" placeholder="ข้อมูลเพิ่มเติม" disabled>
                </div>
            </div>
            <!-- ปุ่มลบเดิมที่ถูกลบออกไปแล้ว -->
        </div>
    `;t.insertAdjacentHTML("beforeend",n),m()};window.removeHelper=function(t){const e=t.closest(".helper-item"),n=e.querySelector('input[name*="[id]"]'),a=e.querySelector(".helper-delete-flag");n&&n.value?(a&&(a.value=1),e.classList.add("d-none")):(e.remove(),typeof m=="function"&&m())};window.toggleHelperInputs=function(t){const e=t.closest(".helper-item"),a=e.querySelector(".helper-name-input").value.trim()!=="",c=e.querySelectorAll(".helper-required-input, .helper-info-input"),p=e.querySelector(".helper-file-name-display");c.forEach(o=>{o.disabled=!a}),p&&(p.disabled=!a,a||(p.value=""))};function m(){document.querySelectorAll(".helper-item").forEach((e,n)=>{e.dataset.index=n;const a=e.querySelector(".helper-title");a&&(a.innerText=`ผู้ช่วยคนที่ ${n+1}`),e.querySelectorAll("input, select").forEach(c=>{const p=c.getAttribute("name");if(p){const o=p.replace(/helpers\[\d+\]/,`helpers[${n}]`);c.setAttribute("name",o)}})})}window.handleEmergencyState=function(){const t=document.getElementById("emergency_name"),e=document.getElementById("emergency_relationship"),n=document.getElementById("emergency_phone");if(t){const a=t.value.trim()!=="";[e,n].forEach(c=>{c&&(c.disabled=!a,c.required=a,a||(c.value=""))})}};document.addEventListener("DOMContentLoaded",function(){const t=document.querySelector('.helper-item[data-index="0"] .helper-name-input');t&&window.toggleHelperInputs(t),window.handleEmergencyState()});document.addEventListener("DOMContentLoaded",function(){const t=document.getElementById("province"),e=document.getElementById("amphure"),n=document.getElementById("tambon"),a=document.getElementById("zipcode");let c=[];const p=t&&t.dataset.current||"",o=e&&e.dataset.current||"",s=n&&n.dataset.current||"";fetch("https://raw.githubusercontent.com/kongvut/thai-province-data/master/api/latest/province_with_district_and_sub_district.json").then(i=>i.json()).then(i=>{c=i,l()}).catch(i=>{console.error("Address API Error:",i),t&&(t.innerHTML='<option value="" disabled>โหลดข้อมูลไม่สำเร็จ</option>')});function l(){t&&(t.innerHTML='<option value="" disabled>เลือกจังหวัด...</option>',c.sort((i,d)=>i.name_th.localeCompare(d.name_th)),c.forEach(i=>{const d=document.createElement("option");d.value=i.name_th,d.textContent=i.name_th,t.appendChild(d)}),p&&(t.value=p,t.dispatchEvent(new Event("change"))))}t&&t.addEventListener("change",function(){e.innerHTML='<option value="" selected disabled>เลือกอำเภอ...</option>',n.innerHTML='<option value="" selected disabled>เลือกตำบล...</option>',a.value="";const i=c.find(d=>d.name_th===this.value);i&&i.districts&&(i.districts.sort((d,r)=>d.name_th.localeCompare(r.name_th)),i.districts.forEach(d=>{const r=document.createElement("option");r.value=d.name_th,r.textContent=d.name_th,e.appendChild(r)}),o&&this.value===p&&(e.value=o,e.dispatchEvent(new Event("change"))))}),e&&e.addEventListener("change",function(){n.innerHTML='<option value="" selected disabled>เลือกตำบล...</option>',a.value="";const d=c.find(r=>r.name_th===t.value)?.districts.find(r=>r.name_th===this.value);d&&d.sub_districts&&(d.sub_districts.sort((r,u)=>r.name_th.localeCompare(u.name_th)),d.sub_districts.forEach(r=>{const u=document.createElement("option");u.value=r.name_th,u.textContent=r.name_th,u.dataset.zipcode=r.zip_code,n.appendChild(u)}),s&&this.value===o&&(n.value=s,n.dispatchEvent(new Event("change"))))}),n&&n.addEventListener("change",function(){const i=this.options[this.selectedIndex];i.dataset.zipcode&&(a.value=i.dataset.zipcode)})});
