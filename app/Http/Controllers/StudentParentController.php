<?php

namespace App\Http\Controllers;

use App\Models\StudentParent;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentParentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $parents = StudentParent::get();
        $students = Student::get();
        return view('admin.parents.index', compact('parents', 'students'));
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
            'name' => 'required',
            'phone' => 'required',
            'email' => "required|unique:student_parents,email,$request->id",
        ]);

        $parent_id = $request->id;
        $name = $request->name;
        $email = $request->email;
        $phone = $request->phone;
        $student_ids = $request->student_id;

        try {
            $result = DB::transaction(function () use ($parent_id, $name, $email, $phone, $student_ids, ) {
                // User::

                StudentParent::updateOrCreate(
                    [
                        'id' => $parent_id
                    ],
                    [
                        'name' => $name,
                        'email' => $email,
                        'phone' => $phone,
                    ]
                );

                if ($student_ids) {
                    Student::whereIn('id', $student_ids)
                        ->update(['parent_id' => $parent_id]);

                    Student::where('parent_id', $parent_id)
                        ->whereNotIn('id', $student_ids)
                        ->update(['parent_id' => null]);
                } else {
                    Student::where('parent_id', $parent_id)
                        ->update(['parent_id' => null]);
                }

                return redirect()->back()->with('success', 'Data wali murid berhasil diperbarui!');
            });

            return $result;
        } catch (\Exception $e) {
            return redirect()->back()->with('warning', 'Data wali murid gagal diperbarui.');
        }
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
        $existStudents = Student::where('parent_id', $id)->exists();
        if ($existStudents) {
            return redirect()->back()->with('warning', 'Kelas sudah digunakan');
        }

        $result = Student::findorfail($id)->delete();
        if ($result) {
            return redirect()->back()->with('warning', 'Data kelas berhasil dihapus! (Silahkan cek trash data kelas)');
        }

        return redirect()->back()->with('danger', 'Data kelas gagal dihapus.');
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
            return redirect()->back()->with('info', 'Data kelas berhasil direstore! (Silahkan cek data kelas)');
        }

        return redirect()->back()->with('warning', 'Data kelas gagal direstore.');
    }

    public function kill($id)
    {
        $result = Student::withTrashed()->findorfail($id)->forceDelete();
        if ($result) {
            return redirect()->back()->with('success', 'Data kelas berhasil dihapus secara permanen');
        }

        return redirect()->back()->with('warning', 'Data kelas gagal dihapus secara permanen');
    }

    public function getEdit(Request $request)
    {
        $parent = StudentParent::with('students')->where('id', $request->id)->first();
        return response()->json($parent);
    }
}
