<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TeacherImport;
use App\Exports\TeacherExport;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teachers = Teacher::with('classroom')->get();
        return view('admin.teachers.index', compact('teachers'));
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
            'nip' => "required|numeric|unique:teachers,nip,$request->nip",
            'name' => 'required',
            'gender' => 'required',
            'phone' => 'required',
        ]);

        Teacher::updateOrCreate(
            [
                'id' => $request->id
            ],
            [
                'nip' => $request->nip,
                'name' => $request->name,
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
            return redirect()->back()->with('warning', 'Data guru berhasil dihapus! (Silahkan cek trash data guru)');
        }

        return redirect()->back()->with('danger', 'Data guru gagal dihapus.');
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
            return redirect()->back()->with('info', 'Data guru berhasil direstore! (Silahkan cek data guru)');
        }

        return redirect()->back()->with('warning', 'Data guru gagal direstore.');
    }

    public function kill($id)
    {
        $result = Teacher::withTrashed()->findorfail($id)->forceDelete();
        if ($result) {
            return redirect()->back()->with('success', 'Data guru berhasil dihapus secara permanen');
        }

        return redirect()->back()->with('warning', 'Data guru gagal dihapus secara permanen');
    }

    public function getEdit(Request $request)
    {
        $teacher = Teacher::select('id', 'nip', 'name', 'gender', 'phone')->where('id', $request->id)->first();
        return response()->json($teacher);
    }

    public function importExcel(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);
        $file = $request->file('file');
        $nama_file = rand() . $file->getClientOriginalName();
        $file->move('excel_file/teacher', $nama_file);
        Excel::import(new TeacherImport, public_path('/excel_file/teacher/' . $nama_file));
        return redirect()->back()->with('success', 'Data Kelas Berhasil Diimport!');
    }

    public function exportExcel()
    {
        $date = Carbon::now()->toDateString();
        return Excel::download(new TeacherExport(), "data-guru-$date.xlsx");
    }
}
