<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Student;
use App\Models\Transaction;
use Illuminate\Http\Request;

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
        $transactions = Transaction::select( 'students.nisn', 'students.name', 'transactions.total_payment', 'transactions.note', 'users.name as admin')
            ->join('users', 'user_id', 'users.id')
            ->join('students', 'student_id', 'students.id')
            ->join('classrooms', 'students.classroom_id', 'classrooms.id')
            ->where('classrooms.id', $id)
            ->orderByDesc('transactions.created_at')->get();
        return view('transactions.index', compact('transactions', 'classroom'));
    }
}
