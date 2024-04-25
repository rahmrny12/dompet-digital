<?php

namespace App\Http\Controllers;

use App\Models\RechargeHistory;
use App\Models\StudentBalance;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Setting;
use App\Models\Classroom;
use Illuminate\Http\Request;

class StudentBalanceController extends Controller
{
    public function entryStudentBalance()
    {
        $classrooms = Classroom::get();

        $setting = Setting::first();
        $service_charge = 0;
        if ($setting) {
            $service_charge = $setting->value('service_charge');
        }

        return view('transactions.entry-balance', compact('classrooms', 'service_charge'));
    }

    public function storeStudentBalance(Request $request)
    {
        $this->validate($request, [
            'classroom_id' => 'required',
            'student_id' => 'required',
        ]);

        $studentBalance = StudentBalance::where('student_id', $request->student_id)->first();

        $oldBalance = 0;
        $newBalance = $request->balance;
        $serviceCharge = Setting::first()->value('service_charge');

        if ($studentBalance) {
            $oldBalance = $studentBalance->current_balance;
            $newBalance += $oldBalance;
        }

        $newBalance -= $serviceCharge;

        RechargeHistory::create([
            'user_id' => auth()->user()->id,
            'student_id' => $request->student_id,
            'amount' => $request->balance,
            'service_charge' => $serviceCharge,
            'current_balance' => $oldBalance,
            'updated_balance' => $newBalance,
        ]);

        StudentBalance::updateOrCreate(
            [
                'student_id' => $request->student_id
            ],
            [
                'current_balance' => $newBalance,
            ]
        );

        return redirect()->back()->with('success', 'Data siswa berhasil diperbarui!');
    }
}
