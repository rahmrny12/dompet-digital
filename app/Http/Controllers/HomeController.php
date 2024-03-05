<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Classroom;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        $user = auth()->user();

        $announcement = Announcement::first();

        $dashboard = (object) [
            'transaction_count' => Transaction::count(),
            'student_count' => Student::count(),
            'teacher_count' => Teacher::count(),
            'classroom_count' => Classroom::count(),
        ];


        return view('home', compact('user', 'dashboard', 'announcement'));
    }

    public function announcement()
    {
        $announcement = Announcement::first();

        return view('admin.announcement', compact('announcement'));
    }

    public function updateAnnouncement(Request $request)
    {
        $this->validate($request, [
            'content' => 'required',
        ]);

        Announcement::updateOrCreate(
            [
                'id' => $request->id
            ],
            [
                'content' => $request->content,
            ]
        );

        return redirect()->route('home')->with('success', 'Data pengumuman berhasil diperbarui!');
    }
}
