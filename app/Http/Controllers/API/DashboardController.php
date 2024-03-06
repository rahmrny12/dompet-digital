<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Classroom;
use App\Models\Announcement;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->role == 'admin') {
            return response()->json([
                'data' => [
                    'transaction_count' => Transaction::count(),
                    'student_count' => Student::count(),
                    'teacher_count' => Teacher::count(),
                    'classroom_count' => Classroom::count(),
                ],
                'message' => 'Success',
                'status_code' => 200
            ]);
        } else {
            return response()->json([
                'data' => Student::with('balance')->find($request->student_id),
                'message' => 'Success',
                'status_code' => 200
            ]);
        }
    }

    public function announcement()
    {
        $user = auth()->user();

        $announcement = Announcement::first();

        return response()->json([
            'data' => $announcement,
            'message' => 'Success',
            'status_code' => 200
        ]);
    }
}
