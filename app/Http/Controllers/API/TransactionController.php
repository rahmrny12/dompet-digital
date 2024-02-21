<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\StudentBalance;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Validator;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|int',
            'total_payment' => 'required|int'
        ]);

        $student_id = $request->student_id;
        $total_payment = $request->total_payment;
        $note = $request->note;

        Transaction::create([
            'student_id' => $student_id,
            'total_payment' => $total_payment,
            'note' => $note,
        ]);

        $student_balance = StudentBalance::where('student_id', $student_id)->first();

        if (!$student_balance || $student_balance->current_balance == 0) {
            return response()->json([
                'message' => 'Saldo siswa kosong.',
                'status_code' => 400
            ], 400);
        }

        $available_balance = $student_balance->current_balance;

        if ($available_balance >= $total_payment) {
            $remaining_balance = $available_balance - $total_payment;
        } else {
            return response()->json([
                'data' => $student_balance,
                'message' => 'Saldo siswa tidak mencukupi.',
                'status_code' => 400
            ], 400);
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
            ],
            'message' => 'Pembayaran berhasil',
            'status_code' => 200
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
