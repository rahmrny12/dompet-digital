<?php

namespace App\Http\Controllers;

use App\Models\StudentParent;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
            $result = DB::transaction(function () use ($parent_id, $name, $email, $phone, $student_ids) {
                $user = User::where('email', $email)->first();

                if ($user) {
                    $user->update(['name' => $name]);
                } else {
                    $latestId = StudentParent::max('id') ?? 0;
                    $newId = $latestId + 1;
                    $password = "WM" . str_pad($newId, 4, '0', STR_PAD_LEFT);

                    $user = User::create([
                        'name' => $name,
                        'email' => $email,
                        'role' => 'parent',
                        'password' => Hash::make($password),
                        'real_password' => $password,
                    ]);
                }

                $parent = StudentParent::updateOrCreate(
                    [
                        'id' => $parent_id
                    ],
                    [
                        'name' => $name,
                        'email' => $email,
                        'phone' => $phone,
                        'user_id' => $user->id,
                    ]
                );
                $parent_id = $parent->id;

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
            dd($e->getMessage());
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
        try {
            $existStudents = Student::where('parent_id', $id)->exists();
            if ($existStudents)
                Student::where('parent_id', $id)->update(['parent_id' => null]);

            $parent = StudentParent::findorfail($id);
            User::where('email', $parent->email)->delete();
            $parent->delete();

            return redirect()->back()->with('warning', 'Data wali murid berhasil dihapus! (Silahkan cek trash data wali murid)');
        } catch (\Exception $e) {
            return redirect()->back()->with('danger', 'Data wali murid gagal dihapus.');
        }
    }

    public function trash()
    {
        $parents = StudentParent::onlyTrashed()->get();
        return view('admin.parents.trash', compact('parents'));
    }

    public function restore($id)
    {
        $id = Crypt::decrypt($id);
        $result = StudentParent::withTrashed()->findorfail($id)->restore();
        if ($result) {
            return redirect()->back()->with('info', 'Data wali murid berhasil direstore! (Silahkan cek data wali murid)');
        }

        return redirect()->back()->with('warning', 'Data wali murid gagal direstore.');
    }

    public function kill($id)
    {
        $result = StudentParent::withTrashed()->findorfail($id)->forceDelete();
        if ($result) {
            return redirect()->back()->with('success', 'Data wali murid berhasil dihapus secara permanen');
        }

        return redirect()->back()->with('warning', 'Data wali murid gagal dihapus secara permanen');
    }

    public function getEdit(Request $request)
    {
        $parent = StudentParent::with('students', 'students.classroom')->where('id', $request->id)->first();
        return response()->json($parent);
    }

    public function getAvailableStudents(Request $request)
    {
        $students = Student::where('parent_id', null)->get();
        return response()->json($students);
    }
}
