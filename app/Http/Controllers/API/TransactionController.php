<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\StudentBalance;
use App\Models\Transaction;
use App\Models\BalanceSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $limitPerPage = $request->limit ?? 5;

        $transactions = Transaction::select( 'students.id as student_id', 'students.name', 'classrooms.id as classroom_id', 'transactions.total_payment', 'transactions.note', 'users.id as admin_id', 'users.name as admin', 'transactions.created_at as transaction_date')
            ->join('users', 'user_id', 'users.id')
            ->join('students', 'student_id', 'students.id')
            ->join('classrooms', 'students.classroom_id', 'classrooms.id')
            ->orderByDesc('transactions.created_at');

        if ($request->classroom_id)
            $transactions = $transactions->where('classrooms.id', $request->classroom_id);

        if ($request->student_id)
            $transactions = $transactions->where('students.id', $request->student_id);

        if (!$transactions->exists())
            return response()->json([
                'message' => 'Empty',
                'status_code' => 404
            ], 404);

        return response()->json([
            'data' => $transactions->simplePaginate($limitPerPage),
            'message' => 'Berhasil',
            'status_code' => 200
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|int',
            'total_payment' => 'required|int'
        ]);

        $user_id = null;
        if (auth()->user()->role)
            $user_id = auth()->user()->id;

        $student_id = $request->student_id;
        $total_payment = $request->total_payment;
        $note = $request->note;

        try {
            $result = DB::transaction(function () use (
                $user_id,
                $student_id,
                $total_payment,
                $note
            ) {
                $setting = BalanceSetting::where('student_id', $student_id)->first();

                if ($setting) {
                    if ($total_payment > $setting->max_limit)
                        throw new \Exception("Batas transaksi tercapai.");

                    $daily_payment = Transaction::where('student_id', $student_id)->whereDate('created_at', '=', Carbon::today())->sum('total_payment');

                    $available_payment = $setting->daily_limit - $daily_payment;

                    $daily_payment = $daily_payment + $total_payment;

                    if ($daily_payment > $setting->daily_limit)
                        throw new \Exception("Batas harian tercapai. Sisa transaksi maksimal : Rp. $available_payment");
                }

                Transaction::create([
                    'user_id' => $user_id,
                    'student_id' => $student_id,
                    'total_payment' => $total_payment,
                    'note' => $note,
                ]);

                $student_balance = StudentBalance::where('student_id', $student_id)->first();

                if (!$student_balance || $student_balance->current_balance == 0) {
                    throw new \Exception('Saldo siswa kosong.');
                }

                $available_balance = $student_balance->current_balance;

                if ($available_balance >= $total_payment) {
                    $remaining_balance = $available_balance - $total_payment;
                } else {
                    throw new \Exception('Saldo siswa tidak mencukupi.');
                }

                StudentBalance::where('student_id', $student_id)->update([
                    'current_balance' => $remaining_balance,
                ]);

                return response()->json([
                    'data' => [
                        'student_id' => $student_id,
                        'student_name' => $student_balance->student->name,
                        'total_payment' => $total_payment,
                        'available_balance' => $available_balance,
                        'remaining_balance' => $remaining_balance,
                        'note' => $note,
                    ],
                    'message' => 'Pembayaran berhasil',
                    'status_code' => 200
                ]);
            });

            return $result;
        } catch (\Exception $e) {
            $data = StudentBalance::select(
                'student_balances.student_id',
                'current_balance',
                'daily_limit',
                'max_limit'
            )->join('balance_settings', 'balance_settings.student_id', '=', 'student_balances.student_id')->where('student_balances.student_id', $student_id)->first();

            return response()->json([
                'data' => $data,
                'message' => $e->getMessage(),
                'status_code' => 400
            ], 400);
        }
    }
}
