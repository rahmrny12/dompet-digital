<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teachers = Teacher::get();
        return view('admin.teacher.index', compact('teachers'));
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
                'nip' => 'required|numeric',
                'name' => 'required',
                'email' => 'required|email',
                'gender' => 'required',
                'phone' => 'required',
            ]);
        } else {
            $this->validate($request, [
                'nip' => 'required|numeric',
                'name' => 'required',
                'email' => 'required|email',
                'gender' => 'required',
                'phone' => 'required',
            ]);
        }

        Teacher::updateOrCreate(
            [
                'id' => $request->id
            ],
            [
                'nip' => $request->nip,
                'name' => $request->name,
                'email' => $request->email,
                'gender' => $request->gender,
                'phone' => $request->phone,
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
        Classroom::where('teacher_id', $id)->update([
            'teacher_id' => null
        ]);

        $result = Teacher::findorfail($id)->delete();
        if ($result) {
            return redirect()->back()->with('warning', 'Data wali kelas berhasil dihapus! (Silahkan cek trash data wali kelas)');
        }

        return redirect()->back()->with('danger', 'Data wali kelas gagal dihapus.');
    }

    public function trash()
    {
        $teachers = Teacher::onlyTrashed()->get();
        return view('admin.teachers.trash', compact('teachers'));
    }

    public function restore($id)
    {
        $id = Crypt::decrypt($id);
        $result = Teacher::withTrashed()->findorfail($id)->restore();
        if ($result) {
            return redirect()->back()->with('info', 'Data wali kelas berhasil direstore! (Silahkan cek data wali kelas)');
        }

        return redirect()->back()->with('warning', 'Data wali kelas gagal direstore.');
    }

    public function kill($id)
    {
        $result = Teacher::withTrashed()->findorfail($id)->forceDelete();
        if ($result) {
            return redirect()->back()->with('success', 'Data wali kelas berhasil dihapus secara permanen');
        }

        return redirect()->back()->with('warning', 'Data wali kelas gagal dihapus secara permanen');
    }

    public function getEdit(Request $request)
    {
        $teacher = Teacher::select('id', 'nip', 'name', 'email', 'gender', 'phone')->where('id', $request->id)->first();
        return response()->json($teacher);
    }
}
