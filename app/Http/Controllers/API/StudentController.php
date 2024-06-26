<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\StudentBalanceResource;
use App\Models\Student;
use App\Models\BalanceSetting;
use App\Models\StudentBalance;
use App\Models\StudentParent;
use App\Models\RechargeHistory;
use App\Models\Setting;
use Crypt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    // public function getBalanceSetting($id)
    // {
    //     $balance_setting = BalanceSetting::with('balance')->where('student_id', $id)->first();

    //     if (!$balance_setting)
    //         return response()->json([
    //             'data' => $balance_setting,
    //             'message' => 'Not Found',
    //             'status_code' => 404
    //         ]);

    //     return response()->json([
    //         'data' => $balance_setting,
    //         'message' => 'Berhasil',
    //         'status_code' => 200
    //     ]);
    // }

    public function getStudent()
    {
        $user_id = auth()->user()->id;
        $parent = StudentParent::where('user_id', $user_id)->first();

        $students = Student::with('classroom')->where('parent_id', $parent->id)->get();

        if (count($students) == 0)
            return response()->json([
                'data' => null,
                'message' => 'Failed',
                'status_code' => 404
            ], 404);

        return response()->json([
            'data' => $students,
            'message' => 'Berhasil',
            'status_code' => 200
        ]);
    }

    public function getStudentBalance($id, Request $request)
    {
        $type = $request->type;

        $student = Student::select(
            'students.id as student_id',
            'students.name as student_name',
            'classrooms.name as classroom_name',
            'nisn',
            DB::raw('ifnull(current_balance,0) as current_balance'),
            'daily_limit',
            'max_limit',
        )->leftJoin('classrooms', 'classrooms.id', '=', 'students.classroom_id')
            ->leftJoin('student_balances', 'student_balances.student_id', '=', 'students.id')
            ->leftJoin('balance_settings', 'balance_settings.student_id', '=', 'students.id')
            ->where(function($query) use ($type, $id) {
                if ($type === 'nisn') {
                    $query->where('students.nisn', $id);
                } else {
                    $query->where('students.id', $id);
                }
            });

        if (!$student->exists()) {
            return response()->json([
                'data' => null,
                'message' => 'Siswa tidak ditemukan',
                'status_code' => 404
            ], 404);
        }

        return response()->json([
            'data' => $student->first(),
            'message' => 'Berhasil',
            'status_code' => 200
        ]);
    }

    public function storeStudentBalance(Request $request)
    {
        $this->validate($request, [
            'student_id' => 'required',
            'balance' => 'required',
        ]);

        $studentBalance = StudentBalance::where('student_id', $request->student_id)->first();

        $insertedBalance = $request->balance;
        $studentId = $request->student_id;

        try {
            $result = DB::transaction(function () use ($studentBalance, $insertedBalance, $studentId, ) {
                $oldBalance = 0;
                $newBalance = $insertedBalance;

                $setting = Setting::first();
                $serviceCharge = 0;
                if ($setting) {
                    $serviceCharge = $setting->value('service_charge');
                }

                if ($studentBalance) {
                    $oldBalance = $studentBalance->current_balance;
                    $newBalance += $oldBalance;
                }

                $newBalance -= $serviceCharge;

                RechargeHistory::create([
                    'user_id' => auth()->user()->id,
                    'student_id' => $studentId,
                    'amount' => $insertedBalance,
                    'service_charge' => $serviceCharge,
                    'current_balance' => $oldBalance,
                    'updated_balance' => $newBalance,
                ]);

                StudentBalance::updateOrCreate(
                    [
                        'student_id' => $studentId
                    ],
                    [
                        'current_balance' => $newBalance,
                    ]
                );

                if (!$studentBalance) {
                    $studentBalance = StudentBalance::where('student_id', $studentId)->first();
                } else {
                    $studentBalance->refresh();
                }

                $studentBalance['old_balance'] = (int) $oldBalance;
                $studentBalance['inserted_balance'] = (int) $insertedBalance;
                $studentBalance['service_charge'] = (int) $serviceCharge;

                return response()->json([
                    'data' => new StudentBalanceResource($studentBalance),
                    'message' => 'Berhasil',
                    'status_code' => 200
                ]);
            });

            return $result;
        } catch (\Exception $e) {
            return response()->json([
                'data' => $studentBalance->refresh(),
                'message' => $e->getMessage(),
                'status_code' => 400
            ], 400);
        }
    }

    public function updateBalanceSetting($id, Request $request)
    {
        $this->validate($request, [
            'daily_limit' => 'required',
            'max_limit' => 'required',
        ]);

        BalanceSetting::updateOrCreate(
            [
                'student_id' => $id
            ],
            [
                'daily_limit' => $request->daily_limit,
                'max_limit' => $request->max_limit,
            ]
        );

        $balance_setting = BalanceSetting::select('student_id', 'daily_limit', 'max_limit')->where('student_id', $id)->first();

        return response()->json([
            'data' => $balance_setting,
            'message' => 'Berhasil',
            'status_code' => 200
        ]);
    }
}
