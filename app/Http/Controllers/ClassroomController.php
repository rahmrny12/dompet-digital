<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Teacher;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $classrooms = Classroom::get();
        $teachers = Teacher::get();
        return view('admin.classroom.index', compact('classrooms', 'teachers'));
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
                'name' => 'required',
                'teacher_id' => 'required',
            ]);
        } else {
            $this->validate($request, [
                'name' => 'required|unique:classrooms',
                'teacher_id' => 'required',
            ]);
        }

        Classroom::updateOrCreate(
            [
                'id' => $request->id
            ],
            [
                'name' => $request->name,
                'teacher_id' => $request->teacher_id,
            ]
        );

        return redirect()->back()->with('success', 'Data kelas berhasil diperbarui!');
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
        $existStudent = Student::where('classroom_id', $id)->exists();
        if ($existStudent) {
            return redirect()->back()->with('warning', 'Kelas sudah digunakan');
        }

        $result = Classroom::findorfail($id)->delete();
        if ($result) {
            return redirect()->back()->with('warning', 'Data kelas berhasil dihapus! (Silahkan cek trash data kelas)');
        }

        return redirect()->back()->with('danger', 'Data kelas gagal dihapus.');
    }

    public function trash()
    {
        $classrooms = Classroom::onlyTrashed()->get();
        return view('admin.classrooms.trash', compact('classrooms'));
    }

    public function restore($id)
    {
        $id = Crypt::decrypt($id);
        $result = Classroom::withTrashed()->findorfail($id)->restore();
        if ($result) {
            return redirect()->back()->with('info', 'Data kelas berhasil direstore! (Silahkan cek data kelas)');
        }

        return redirect()->back()->with('warning', 'Data kelas gagal direstore.');
    }

    public function kill($id)
    {
        $result = Classroom::withTrashed()->findorfail($id)->forceDelete();
        if ($result) {
            return redirect()->back()->with('success', 'Data kelas berhasil dihapus secara permanen');
        }

        return redirect()->back()->with('warning', 'Data kelas gagal dihapus secara permanen');
    }

    public function getEdit(Request $request)
    {
        $classroom = Classroom::select('id', 'name', 'teacher_id')->where('id', $request->id)->first();
        return response()->json($classroom);
    }
}
