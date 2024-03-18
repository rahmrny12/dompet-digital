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
        $setting = Setting::first();

        if ($setting)
            return response()->json([
                'data' => $setting,
                'message' => 'Success',
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
            'service_charge' => 'required',
        ]);

        $exist = Setting::exists();

        if ($exist) {
            Setting::query()->update(['service_charge' => $request->service_charge]);
        } else {
            Setting::create(['service_charge' => $request->service_charge]);
        }

        $setting = Setting::first();

        return response()->json([
            'data' => $setting,
            'message' => 'Pengaturan berhasil diperbarui!',
            'status_code' => 200
        ]);
    }
}
