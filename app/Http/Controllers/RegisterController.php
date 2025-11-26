<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\DriverHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log; // เพิ่ม Log เพื่อช่วย debug

class RegisterController extends Controller
{
    public function showForm() {
        return view('driver_register'); 
    }

    public function saveForm(Request $request)
    {
        $request->validate([
            'first_last_name'   => 'required|string|max:255',
            'driver_phone'      => 'required|string|max:20',
            'address_details'   => 'required|string',
            'province'          => 'required',
            'doc_license'       => 'nullable|image|max:10240',
            'helpers.*.name'    => 'nullable|string',
            'helpers.*.idcard'  => 'nullable|image|max:10240',
        ]);

        DB::beginTransaction();
        try {
            $driver = new Driver();
            $driver->driver_name  = trim(($request->prefix ?? '').' '.$request->first_last_name);
            $driver->driver_phone = $request->driver_phone;

            $driver->address_line = $request->address_details;
            $driver->street       = $request->road;
            $driver->soi          = $request->soi;
            $driver->province     = $request->province;
            $driver->district     = $request->district;
            $driver->sub_district = $request->sub_district;
            $driver->postal_code  = $request->zipcode;

            // Emergency contact
            $ecName = trim($request->emergency_name ?? '');

            if ($ecName !== '') {
                $prefix = $request->emergency_prefix ?? '';
                $driver->ec_name     = trim($prefix.' '.$ecName);
                $driver->ec_relation = $request->emergency_relationship;
                $driver->ec_phone    = $request->emergency_phone;
            } else {
                // ถ้าไม่กรอกชื่อเลย ไม่บันทึก emergency contact
                $driver->ec_name     = null;
                $driver->ec_relation = null;
                $driver->ec_phone    = null;
            }

            $upload = function($file, $folder) {
                if ($file && $file->isValid()) {
                    $name = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
                    return $file->storeAs("uploads/{$folder}", $name, 'public');
                }
                return null;
            };

            // เอกสาร
            $driver->file_driving_license = $upload($request->file('doc_license'), 'docs');
            $driver->file_profile_photo   = $upload($request->file('doc_face'), 'docs');
            $driver->file_id_card         = $upload($request->file('doc_idcard'), 'docs');
            $driver->file_vehicle_reg     = $upload($request->file('doc_car_registration'), 'docs');

            // รูปรถ
            $driver->img_truck_front = $upload($request->file('car_photo_front'), 'trucks');
            $driver->img_truck_rear  = $upload($request->file('car_photo_back'), 'trucks');
            $driver->img_truck_left  = $upload($request->file('car_photo_left'), 'trucks');
            $driver->img_truck_right = $upload($request->file('car_photo_right'), 'trucks');

            // PPE
            $driver->img_ppe_vest     = $upload($request->file('safety_vest'), 'ppe');
            $driver->img_ppe_shoes    = $upload($request->file('safety_shoes'), 'ppe');
            $driver->img_wheel_chock  = $upload($request->file('safety_wheel_chock'), 'ppe');

            $driver->save();

            // ผู้ช่วย (สำคัญ: อ้างไฟล์ด้วย index)
            foreach ($request->input('helpers', []) as $i => $h) {
                if (!empty($h['name'])) {
                    $helper = new DriverHelper();
                    $helper->driver_id = $driver->id;

                    $prefix           = $h['prefix'] ?? '';
                    $helper->dh_name  = trim($prefix.' '.$h['name']);
                    $helper->dh_phone = $h['phone'] ?? null;
                    $helper->dh_info  = $h['info'] ?? null;

                    if ($request->hasFile("helpers.$i.idcard")) {
                        $file = $request->file("helpers.$i.idcard");
                        $helper->dh_idcard_path = $upload($file, 'helpers');
                    }

                    $helper->save();
                }
            }

            DB::commit();
            return redirect()->route('register.success');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Registration Error: ".$e->getMessage().' @'.$e->getFile().':'.$e->getLine());
            // ให้เห็นข้อผิดพลาดบนหน้าเดิม
            return back()->withErrors(['save' => $e->getMessage()])->withInput();
        }
    }

    public function edit(Driver $driver)
    {
        // โหลด helpers ติดไปด้วย
        $driver->load('helpers');
        return view('driver_edit', compact('driver'));
    }

    public function update(Request $request, Driver $driver)
    {
        $request->validate([
            'first_last_name'   => ['required','string','max:255'],
            'driver_phone'      => ['required','string','max:20'],
            'address_details'   => ['required','string'],
            'province'          => ['required','string'],

            // ไฟล์ (อัปไม่อัปก็ได้ แต่ถ้าอัปต้องเป็นภาพ ≤10MB)
            'doc_license'       => ['nullable','image','max:10240','mimes:jpg,jpeg,png,webp'],
            'doc_face'          => ['nullable','image','max:10240','mimes:jpg,jpeg,png,webp'],
            'doc_idcard'        => ['nullable','image','max:10240','mimes:jpg,jpeg,png,webp'],
            'doc_car_registration'=>['nullable','image','max:10240','mimes:jpg,jpeg,png,webp'],
            'car_photo_front'   => ['nullable','image','max:10240','mimes:jpg,jpeg,png,webp'],
            'car_photo_back'    => ['nullable','image','max:10240','mimes:jpg,jpeg,png,webp'],
            'car_photo_left'    => ['nullable','image','max:10240','mimes:jpg,jpeg,png,webp'],
            'car_photo_right'   => ['nullable','image','max:10240','mimes:jpg,jpeg,png,webp'],
            'safety_vest'       => ['nullable','image','max:10240','mimes:jpg,jpeg,png,webp'],
            'safety_shoes'      => ['nullable','image','max:10240','mimes:jpg,jpeg,png,webp'],
            'safety_wheel_chock'=> ['nullable','image','max:10240','mimes:jpg,jpeg,png,webp'],

            // ผู้ช่วย: ถ้ากรอกชื่อ → บังคับเบอร์ & บัตร (แก้ตามต้องการ)
            'helpers'               => ['nullable','array'],
            'helpers.*.id'          => ['nullable','integer'],
            'helpers.*.name'        => ['nullable','string','max:255'],
            'helpers.*.phone'       => ['nullable','string','max:20'],
            'helpers.*.info'        => ['nullable','string','max:500'],
            'helpers.*.idcard'      => ['nullable','image','max:10240','mimes:jpg,jpeg,png,webp'],
            'helpers.*._delete'     => ['nullable','boolean'],
        ]);

        DB::beginTransaction();
        try {
            // map input → driver fields
            $driver->driver_name  = trim(($request->prefix ?? '').' '.$request->first_last_name);
            $driver->driver_phone = $request->driver_phone;

            $driver->address_line = $request->address_details;
            $driver->street       = $request->road;
            $driver->soi          = $request->soi;
            $driver->province     = $request->province;
            $driver->district     = $request->district;
            $driver->sub_district = $request->sub_district;
            $driver->postal_code  = $request->zipcode;

            $ecName = trim($request->emergency_name ?? '');

            if ($ecName !== '') {
                $prefix = $request->emergency_prefix ?? '';
                $driver->ec_name     = trim($prefix.' '.$ecName);
                $driver->ec_relation = $request->emergency_relationship;
                $driver->ec_phone    = $request->emergency_phone;
            } else {
                $driver->ec_name     = null;
                $driver->ec_relation = null;
                $driver->ec_phone    = null;
            }

            // helper ฟังก์ชันอัปโหลด+ลบไฟล์เก่า
            $replace = function(string $input, string $folder, string $attr) use ($request, $driver) {
                if ($request->hasFile($input)) {
                    if ($driver->$attr) Storage::disk('public')->delete($driver->$attr);
                    $file = $request->file($input);
                    $name = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
                    $driver->$attr = $file->storeAs("uploads/{$folder}", $name, 'public');
                }
            };

            // เอกสาร
            $replace('doc_license', 'docs', 'file_driving_license');
            $replace('doc_face', 'docs', 'file_profile_photo');
            $replace('doc_idcard', 'docs', 'file_id_card');
            $replace('doc_car_registration', 'docs', 'file_vehicle_reg');

            // รูปรถ
            $replace('car_photo_front', 'trucks', 'img_truck_front');
            $replace('car_photo_back',  'trucks', 'img_truck_rear');
            $replace('car_photo_left',  'trucks', 'img_truck_left');
            $replace('car_photo_right', 'trucks', 'img_truck_right');

            // PPE
            $replace('safety_vest',        'ppe', 'img_ppe_vest');
            $replace('safety_shoes',       'ppe', 'img_ppe_shoes');
            $replace('safety_wheel_chock', 'ppe', 'img_wheel_chock');

            $driver->save();

            // ------- อัปเดตผู้ช่วย -------
            $incoming = $request->input('helpers', []);

            // 1) ลบรายการที่ติ๊ก _delete หรือเว้นชื่อว่าง (ถ้ามี id)
            foreach ($incoming as $i => $h) {
                if (!empty($h['id']) && (!empty($h['_delete']) || empty($h['name']))) {
                    $helper = DriverHelper::where('driver_id', $driver->id)->find($h['id']);
                    if ($helper) {
                        if ($helper->dh_idcard_path) Storage::disk('public')->delete($helper->dh_idcard_path);
                        $helper->delete();
                    }
                }
            }

            // 2) อัปเดต/เพิ่ม
            foreach ($incoming as $i => $h) {
                // ข้ามถ้าติ๊กลบหรือไม่มีชื่อ
                if (!empty($h['_delete']) || empty($h['name'])) continue;

                if (!empty($h['id'])) {
                    // update
                    $helper = DriverHelper::where('driver_id', $driver->id)->find($h['id']);
                    if (!$helper) continue;

                    $helper->dh_name  = trim(($h['prefix'] ?? '').' '.($h['name'] ?? ''));
                    $helper->dh_phone = $h['phone'] ?? null;
                    $helper->dh_info  = $h['info'] ?? null;

                    if ($request->hasFile("helpers.$i.idcard")) {
                        if ($helper->dh_idcard_path) Storage::disk('public')->delete($helper->dh_idcard_path);
                        $file = $request->file("helpers.$i.idcard");
                        $name = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
                        $helper->dh_idcard_path = $file->storeAs('uploads/helpers', $name, 'public');
                    }

                    $helper->save();
                } else {
                    // create ใหม่
                    $new = new DriverHelper();
                    $new->driver_id = $driver->id;
                    $new->dh_name   = trim(($h['prefix'] ?? '').' '.($h['name'] ?? ''));
                    $new->dh_phone  = $h['phone'] ?? null;
                    $new->dh_info   = $h['info'] ?? null;

                    if ($request->hasFile("helpers.$i.idcard")) {
                        $file = $request->file("helpers.$i.idcard");
                        $name = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
                        $new->dh_idcard_path = $file->storeAs('uploads/helpers', $name, 'public');
                    }
                    $new->save();
                }
            }

            DB::commit();
            return redirect()->route('drivers.update.success')
                 ->with('success', 'อัปเดตข้อมูลเรียบร้อยแล้ว');

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("[Driver Update] ".$e->getMessage().' @'.$e->getFile().':'.$e->getLine());
            return back()->withErrors(['save' => $e->getMessage()])->withInput();
        }
    }
}