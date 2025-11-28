<?php

use Illuminate\Support\Facades\Route;
// 1. ดึง Controller มาใช้
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\RegisterController; 
use App\Http\Controllers\DriverJobController;


Route::get('/', function () {
    return view('home');
});

// 2. สร้างเส้นทาง
// แบบ GET: สำหรับเปิดหน้าฟอร์ม
Route::get('/register', [RegisterController::class, 'showForm'])->name('register.form');

// แบบ POST: สำหรับกดปุ่ม "ยืนยัน" เพื่อบันทึกข้อมูล
Route::post('/register', [RegisterController::class, 'saveForm'])->name('register.save');

Route::get('/register/success', function () {
    return view('driver_success');
})->name('register.success');

// หน้าแก้ไข
Route::get('/drivers/{driver}/edit', [RegisterController::class, 'edit'])->name('drivers.edit');
// อัปเดต
Route::put('/drivers/{driver}', [RegisterController::class, 'update'])->name('drivers.update');

Route::get('/drivers/update/success', function () {
    return view('driver_edit_success');
})->name('drivers.update.success');



Route::get('/shipping/{id}', [ShippingController::class, 'show'])
     ->name('shipping.show');
Route::get('/job/mock', [ShippingController::class, 'showMock'])
    ->name('job.mock');


Route::get('/driver/jobs', [DriverJobController::class, 'index'])->name('driver.jobs');
Route::get('/driver/jobs/{id}', [DriverJobController::class, 'show'])->name('driver.jobs.show');
Route::get('/driver/jobs/{id}/accept', [DriverJobController::class, 'accept'])->name('driver.jobs.accept');

