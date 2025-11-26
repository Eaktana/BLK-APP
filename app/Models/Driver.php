<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    // เปิดให้บันทึกทุกช่องได้ (ถ้าอยากปลอดภัยกว่าใช้ fillable)
    protected $guarded = [];

    // ความสัมพันธ์กับ DriverHelper
    public function helpers()
    {
        return $this->hasMany(DriverHelper::class);
    }

    /** Accessor สำหรับแปลง path เป็น URL ของไฟล์ (เช่นรูป, เอกสาร) */
    public function getFileUrl($path)
    {
        return $path ? asset('storage/' . $path) : null;
    }
}
