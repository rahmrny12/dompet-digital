<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Classroom;
use Illuminate\Http\Request;

class ClassAdvisorController extends Controller
{
    public function index() {
        $teachers = Teacher::with('classroom')->get();
        $classrooms = Classroom::with('teacher')->get();

        $class_advisors = Teacher::has('classroom')->with(['classroom'])->get();
        return view('admin.class_advisor.index', compact('class_advisors', 'teachers', 'classrooms'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'teacher_id' => 'required',
            'classroom_id' => 'required',
        ]);

        Classroom::where('teacher_id', $request->teacher_id)->update(['teacher_id' => null]);
        Classroom::find($request->classroom_id)->update(['teacher_id' => $request->teacher_id]);

        return redirect()->back()->with('success', 'Data wali kelas berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Classroom::where('teacher_id', $id)->update([
            'teacher_id' => null
        ]);

        return redirect()->back()->with('danger', 'Data wali kelas berhasil dihapus.');
    }

    public function getEdit(Request $request)
    {
        $teacher = Teacher::select('teachers.id as teacher_id', 'classrooms.id as classroom_id')->join('classrooms', 'classrooms.teacher_id', 'teachers.id')->where('teachers.id', $request->id)->first();
        return response()->json($teacher);
    }
}
