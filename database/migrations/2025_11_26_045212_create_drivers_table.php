<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();

            // 1. ข้อมูลพนักงาน (Personnel Info)
            // ใช้ full_name แทน driver_name ให้ดูเป็นทางการ
            // ใช้ mobile_no แทน phone ให้รู้ว่าเป็นเบอร์มือถือ
            $table->string('driver_name');
            $table->string('driver_phone'); 

            // 2. ที่อยู่ (Current Address)
            // แยก address_line (บ้านเลขที่/หมู่) ออกจากส่วนภูมิศาสตร์
            $table->string('address_line')->nullable();
            $table->string('street')->nullable();// เปลี่ยน road เป็น street
            $table->string('soi')->nullable();
            $table->string('sub_district')->nullable(); // ตำบล/แขวง
            $table->string('district')->nullable();     // อำเภอ/เขต
            $table->string('province')->nullable();     // จังหวัด
            $table->string('postal_code')->nullable(); // zipcode -> postal_code (ทางการกว่า)

            // 3. เอกสารสำคัญ (Verification Documents)
            // ใช้ prefix 'file_' สื่อว่าเป็นไฟล์เอกสาร
            $table->string('file_driving_license')->nullable(); // ใบขับขี่
            $table->string('file_profile_photo')->nullable();   // รูปถ่ายหน้าตรง
            $table->string('file_id_card')->nullable();         // บัตรประชาชน
            $table->string('file_vehicle_reg')->nullable();     // เล่มทะเบียนรถ

            // 4. สภาพรถบรรทุก (Truck Inspection Photos)
            // เปลี่ยน car -> truck และใช้ศัพท์เทคนิค (Rear แทน Back)
            $table->string('img_truck_front')->nullable();
            $table->string('img_truck_rear')->nullable();  // ด้านท้าย (Technical term)
            $table->string('img_truck_left')->nullable();
            $table->string('img_truck_right')->nullable();

            // 5. อุปกรณ์ความปลอดภัย (PPE & Safety Equipment)
            // PPE = Personal Protective Equipment
            $table->string('img_ppe_vest')->nullable();    // เสื้อสะท้อนแสง
            $table->string('img_ppe_shoes')->nullable();   // รองเท้าหัวเหล็ก
            $table->string('img_wheel_chock')->nullable(); // หมอนรองล้อ

            // 6. ผู้ติดต่อฉุกเฉิน (Emergency Contact - EC)
            // ใช้ prefix 'ec_' ย่อมาจาก Emergency Contact
            $table->string('ec_name')->nullable();
            $table->string('ec_relation')->nullable();
            $table->string('ec_phone')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
