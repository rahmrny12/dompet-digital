<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Crypt;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function show()
    {
        $setting = Setting::select('service_charge', 'admin_phone_number')->first();

        if ($setting)
            return response()->json([
                'data' => $setting,
                'message' => 'Berhasil',
                'status_code' => 200
            ]);

        return response()->json([
            'data' => $setting,
            'message' => 'Not Found',
            'status_code' => 404
        ], 404);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'service_charge' => 'nullable',
            'admin_phone_number' => 'nullable',
        ]);

        $exist = Setting::exists();

        if ($exist) {
            $updates = [];

            if ($request->has('service_charge')) {
                $updates['service_charge'] = $request->service_charge;
            }

            if ($request->has('admin_phone_number')) {
                $updates['admin_phone_number'] = $request->admin_phone_number;
            }

            Setting::query()->update($updates);
        } else {
            Setting::create([
                'service_charge' => $request->service_charge,
                'admin_phone_number' => $request->admin_phone_number
            ]);
        }

        $setting = Setting::select('service_charge', 'admin_phone_number')->first();

        return response()->json([
            'data' => $setting,
            'message' => 'Pengaturan berhasil diperbarui!',
            'status_code' => 200
        ]);
    }

    public function getServiceCharge()
    {
        $setting = Setting::first();
        $data['service_charge'] = 0;
        if ($setting) {
            $data['service_charge'] = $setting->value('service_charge');
        }

        return response()->json([
            'data' => $data,
            'message' => 'Berhasil',
            'status_code' => 200
        ]);
    }
}
