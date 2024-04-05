<?php

namespace App\Http\Controllers;

use App\Models\BalanceSetting;
use App\Models\Student;
use App\Models\Classroom;
use App\Models\Transaction;
use App\Models\StudentParent;
use Crypt;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index($id)
    {
        $selected_classroom = Classroom::find($id);

        $students = Student::where('classroom_id', $id)->get();
        $classrooms = Classroom::get();
        $parents = StudentParent::get();
        return view('admin.student.index', compact('students', 'classrooms', 'parents', 'selected_classroom'));
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
        $this->validate($request, [
            'nisn' => "required|unique:students,nisn,$request->id",
            'name' => 'required',
            'classroom_id' => 'required',
            'gender' => 'required',
            // 'birthplace' => 'required',
            // 'birthdate' => 'required',
            'nfc_id' => 'required',
        ]);

        Student::updateOrCreate(
            [
                'id' => $request->id
            ],
            [
                'nisn' => $request->nisn,
                'name' => $request->name,
                'classroom_id' => $request->classroom_id,
                'gender' => $request->gender,
                'birthplace' => $request->birthplace,
                'birthdate' => $request->birthdate,
                'parent_id' => $request->parent_id,
                'nfc_id' => $request->nfc_id,
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
        $student = Student::with('balance')->find($id);
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
        $students = Student::select(
            'id',
            'nisn',
            'name',
            'classroom_id',
            'gender',
            'birthplace',
            'birthdate',
            'parent_id',
            'nfc_id',
        )->where('id', $id)->first();
        return response()->json($students);
    }

    public function getBalanceSetting($id)
    {
        $student = Student::with('balance_setting')->find($id);
        return response()->json($student);
    }

    public function updateBalanceSetting(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'daily_limit' => 'required',
            'max_limit' => 'required',
        ]);

        BalanceSetting::updateOrCreate(
            [
                'student_id' => $request->id
            ],
            [
                'daily_limit' => $request->daily_limit,
                'max_limit' => $request->max_limit,
            ]
        );

        return redirect()->back()->with('success', 'Data siswa berhasil diperbarui!');
    }

    public function getStudentsByClassroom($id)
    {
        $students = Student::with('balance')->where('classroom_id', $id)->get();
        return response()->json($students);
    }
}
