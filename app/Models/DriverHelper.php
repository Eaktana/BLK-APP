<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverHelper extends Model
{
    // ถ้าชื่อ table เป็น driver_helpers ตาม migration ข้อนี้ไม่จำเป็น
    // protected $table = 'driver_helpers';

    // ปลอดภัยกว่า guarded=[]
    protected $fillable = [
        'driver_id',
        'dh_name',
        'dh_phone',
        'dh_idcard_path',
        'dh_info',
    ];

    // timestamps = true โดยปริยาย ไม่ต้องระบุเพิ่มก็ได้
    // public $timestamps = true;

    /** ความสัมพันธ์กับ Driver */
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    /** Accessor: URL สำหรับแสดงภาพบัตรผู้ช่วย */
    public function getIdcardUrlAttribute(): ?string
    {
        return $this->dh_idcard_path
            ? asset('storage/'.$this->dh_idcard_path)
            : null;
    }
}
