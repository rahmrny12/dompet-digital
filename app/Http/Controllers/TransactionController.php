<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Student;
use App\Models\Transaction;
use App\Models\RechargeHistory;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function classrooms()
    {
        $classrooms = Classroom::get();
        return view('transactions.classrooms', compact('classrooms'));
    }

    public function index($id)
    {
        $classroom = Classroom::find($id)->first();
        $transactions = Transaction::select('transactions.created_at', 'students.nisn', 'students.name', 'transactions.total_payment', 'transactions.note', 'users.name as admin')
            ->join('users', 'user_id', 'users.id')
            ->join('students', 'student_id', 'students.id')
            ->join('classrooms', 'students.classroom_id', 'classrooms.id')
            ->where('classrooms.id', $id)
            ->orderByDesc('transactions.created_at')->get();
        return view('transactions.index', compact('transactions', 'classroom'));
    }

    public function report(Request $request)
    {
        $from_date = $request->from_date ?: now();
        $to_date = $request->to_date ?: now();

        $recharge = RechargeHistory::with('student')
            ->whereDate('created_at', '>=', $from_date)
            ->whereDate('created_at', '<=', $to_date)
            ->get();

        return view('transactions.report', compact('recharge'));
    }
}
