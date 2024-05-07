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
    public function index()
    {
        $transactions = Transaction::select('transactions.created_at', 'students.nisn', 'students.name', 'transactions.total_payment', 'transactions.note', 'users.name as admin')
            ->join('users', 'user_id', 'users.id')
            ->join('students', 'student_id', 'students.id')
            ->join('classrooms', 'students.classroom_id', 'classrooms.id')
            ->orderByDesc('transactions.created_at')->get();
        return view('admin.transactions.index', compact('transactions'));
    }
}
