<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Classroom;
use App\Models\Transaction;
use Crypt;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function classrooms()
    {
        $classrooms = Classroom::get();
        return view('admin.student.classrooms', compact('classrooms'));
    }

    public function index($id)
    {
        $students = Student::get();
        $classrooms = Classroom::get();
        return view('admin.student.index', compact('students', 'classrooms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        if ($request->id != null) {
            $this->validate($request, [
                'nisn' => 'required',
                'name' => 'required',
                'classroom_id' => 'required',
            ]);
        } else {
            $this->validate($request, [
                'nisn' => 'required|unique:students',
                'name' => 'required',
                'classroom_id' => 'required',
            ]);
        }

        Student::updateOrCreate(
            [
                'id' => $request->id
            ],
            [
                'nisn' => $request->nisn,
                'name' => $request->name,
                'classroom_id' => $request->classroom_id,
            ]
        );

        return redirect()->back()->with('success', 'Data siswa berhasil diperbarui!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id = Crypt::decrypt($id);
        $student = Student::find($id);
        return view('admin.student.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
        $existTransaction = Transaction::where('student_id', $id)->exists();
        if ($existTransaction) {
            return redirect()->back()->with('warning', 'Siswa ini sudah pernah melakukan transaksi');
        }

        $result = Student::findorfail($id)->delete();
        if ($result) {
            return redirect()->back()->with('warning', 'Data siswa berhasil dihapus! (Silahkan cek trash data siswa)');
        }

        return redirect()->back()->with('danger', 'Data siswa gagal dihapus.');
    }

    public function trash()
    {
        $students = Student::onlyTrashed()->get();
        return view('admin.students.trash', compact('students'));
    }

    public function restore($id)
    {
        $id = Crypt::decrypt($id);
        $result = Student::withTrashed()->findorfail($id)->restore();
        if ($result) {
            return redirect()->back()->with('info', 'Data siswa berhasil direstore! (Silahkan cek data siswa)');
        }

        return redirect()->back()->with('warning', 'Data siswa gagal direstore.');
    }

    public function kill($id)
    {
        $result = Student::withTrashed()->findorfail($id)->forceDelete();
        if ($result) {
            return redirect()->back()->with('success', 'Data siswa berhasil dihapus secara permanen');
        }

        return redirect()->back()->with('warning', 'Data siswa gagal dihapus secara permanen');
    }

    public function getEdit($id)
    {
        $students = Student::select('id', 'nisn','name','classroom_id')->where('id', $id)->first();
        return response()->json($students);
    }

    public function getBalanceSetting($id)
    {
        $student = Student::with(['balance', 'balance_setting'])->find($id)->first();
        return response()->json($student);
    }

    public function getStudentsByClassroom($id)
    {
        $students = Student::with('balance')->where('classroom_id', $id)->get();
        return response()->json($students);
    }
}
