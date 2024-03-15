<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\StudentBalanceResource;
use App\Models\Student;
use App\Models\BalanceSetting;
use App\Models\StudentBalance;
use App\Models\StudentParent;
use App\Models\RechargeHistory;
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
    //         'message' => 'Success',
    //         'status_code' => 200
    //     ]);
    // }

    public function getStudent()
    {
        $user_id = auth()->user()->id;
        $parent = StudentParent::where('user_id', $user_id)->first();

        $students = Student::where('parent_id', $parent->id)->get();

        if (count($students) == 0)
            return response()->json([
                'data' => null,
                'message' => 'Failed',
                'status_code' => 404
            ]);

        return response()->json([
            'data' => $students,
            'message' => 'Success',
            'status_code' => 200
        ]);
    }

    public function getStudentBalance($id)
    {
        $balance = StudentBalance::select(
            'student_balances.student_id',
            'current_balance',
            'daily_limit',
            'max_limit'
        )->leftJoin('balance_settings', 'balance_settings.student_id', '=', 'student_balances.student_id')->where('student_balances.student_id', $id);

        if (!$balance->exists())
            return response()->json([
                'message' => 'Empty',
                'status_code' => 404
            ]);

        return response()->json([
            'data' => $balance->first(),
            'message' => 'Success',
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

                if ($studentBalance) {
                    $oldBalance = $studentBalance->current_balance;
                    $newBalance += $oldBalance;
                }

                RechargeHistory::create([
                    'user_id' => auth()->user()->id,
                    'student_id' => $studentId,
                    'amount' => $insertedBalance,
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

                return response()->json([
                    'data' => new StudentBalanceResource($studentBalance),
                    'message' => 'Success',
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

        $balance_setting = BalanceSetting::find($id);

        return response()->json([
            'data' => $balance_setting,
            'message' => 'Success',
            'status_code' => 200
        ]);
    }
}
