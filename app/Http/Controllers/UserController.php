<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::whereRole('admin')->get();

        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'username' => "required|unique:users,username,$request->id",
        ];
        if (empty($request->id)) {
            $rules['password'] = 'required';
        }
        $this->validate($request, $rules);

        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'role' => 'admin',
        ];
        if (!empty($request->password)) {
            $data['password'] = Hash::make($request->password);
            $data['real_password'] = $request->password;
        }

        User::updateOrCreate(
            [
                'id' => $request->id
            ],
            $data
        );

        return redirect()->back()->with('success', 'Data admin berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findorfail($id);
        if ($user->id == 1) {
            return redirect()->back()->with('error', 'User ini adalah admin utama.');
        } else {
            $user->delete();
            return redirect()->back()->with('warning', 'Data user berhasil dihapus! (Silahkan cek trash data user)');
        }
    }

    public function getEdit(Request $request)
    {
        $admin = User::find($request->id);

        return response()->json($admin);
    }

    public function profile()
    {
        return view('user.pengaturan');
    }

    public function trash()
    {
        $classrooms = User::onlyTrashed()->where('role', 'admin')->get();
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
}
