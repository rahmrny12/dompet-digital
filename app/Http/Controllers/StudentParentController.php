<?php

namespace App\Http\Controllers;

use App\Models\StudentParent;
use App\Models\Student;
use App\Models\User;
use App\Imports\ParentImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Crypt;

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
        $students = Student::whereDoesntHave('parent')->get();

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
            'email' => "nullable|sometimes|unique:student_parents,email,$request->email",
            'username' => 'required',
        ]);

        $parent_id = $request->id;
        $name = $request->name;
        $email = $request->email;
        $username = $request->username;
        $address = $request->address;
        $phone = $request->phone;
        $student_ids = $request->student_id;

        try {
            $result = DB::transaction(function () use ($parent_id, $name, $email, $phone, $student_ids, $username, $address) {
                $user = User::where('email', $email)->first();

                if ($user) {
                    // $user->update(['name' => $name, 'username' => $username, 'email' => $email,  'phone' => $phone]);
                } else {
                    $latestId = StudentParent::max('id') ?? 0;
                    $newId = $latestId + 1;
                    $password = "WM" . str_pad($newId, 4, '0', STR_PAD_LEFT);

                    $user = User::create([
                        'name' => $name,
                        'email' => $email,
                        'username' => $username,
                        'phone' => $phone,
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
                        'username' => $username,
                        'address' => $address,
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
            return redirect()->back()->with('warning', 'Data wali murid gagal diperbarui.');
        }
    }

    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'id' => "required",
            'password' => 'required',
        ]);

        $id = $request->id;
        $password = $request->password;

        try {
            $result = DB::transaction(function () use ($id, $password) {
                $user_id = StudentParent::select('user_id')->find($id);

                User::where('id', $user_id)->update(['password' => Hash::make($password), 'real_password' => $password]);

                return redirect()->back()->with('success', 'Password user berhasil diperbarui!');
            });

            return $result;
        } catch (\Exception $e) {
            return redirect()->back()->with('warning', 'Password gagal diperbarui.');
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
            User::findOrFail($parent->user_id)->delete();
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
        try {
            DB::beginTransaction();

            $id = Crypt::decrypt($id);
            $parent = StudentParent::withTrashed()->findOrFail($id);
            User::withTrashed()->findOrFail($parent->user_id)->restore();
            $parent->restore();

            DB::commit();

            return redirect()->back()->with('info', 'Data wali murid berhasil direstore! (Silahkan cek data wali murid)');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('warning', 'Data wali murid gagal direstore.');
        }

    }

    public function kill($id)
    {
        try {
            DB::beginTransaction();

            $id = Crypt::decrypt($id);
            $parent = StudentParent::withTrashed()->findOrFail($id);
            $parent->forceDelete();
            User::withTrashed()->findOrFail($parent->user_id)->forceDelete();

            DB::commit();

            return redirect()->back()->with('success', 'Data wali murid berhasil dihapus secara permanen.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('warning', 'Data wali murid gagal dihapus secara permanen.');
        }
    }

    public function getEdit(Request $request)
    {
        $parent = StudentParent::with('students', 'students.classroom', 'user')->where('id', $request->id)->first();

        $students = Student::with('classroom')->whereDoesntHave('parent', function ($query) use ($parent) {
            $query->where('parent_id', '<>', $parent->id);
        })->orWhereDoesntHave('parent')->get();

        return response()->json([$parent, $students]);
    }

    public function getEditPassword(Request $request)
    {
        $password = StudentParent::join('users', 'users.id', 'student_parents.user_id')->where('student_parents.id', $request->id)->first();

        return response()->json($password);
    }

    public function getAvailableStudents(Request $request)
    {
        $students = Student::where('parent_id', null)->get();
        return response()->json($students);
    }

    public function importExcel(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);
        $file = $request->file('file');
        $nama_file = rand() . $file->getClientOriginalName();
        $file->move('excel_file/parent', $nama_file);
        Excel::import(new ParentImport, public_path('/excel_file/parent/' . $nama_file));
        return redirect()->back()->with('success', 'Data Wali Murid Berhasil Diimport!');
    }
}
