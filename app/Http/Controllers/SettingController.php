<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Crypt;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function show()
    {
        $setting = Setting::first();

        return view('admin.setting', compact('setting'));
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

        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui!');
    }
}
