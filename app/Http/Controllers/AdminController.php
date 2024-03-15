<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        $admins = User::whereRole('admin')->get();

        return view('admin.admins.index', compact('admins'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => "required|unique:users,email,$request->id",
        ];
        if (empty($request->id)) {
            $rules['password'] = 'required';
        }
        $this->validate($request, $rules);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'admin',
        ];
        if(!empty($request->password)) {
            $data['password'] = Hash::make($request->password);
            $data['real_password'] = $request->password;
        }

        User::updateOrCreate(
            [
                'id' => $request->id
            ],
            $data
        );

        return redirect()->back()->with('success', 'Data wali murid berhasil diperbarui!');
    }

    public function getEdit(Request $request)
    {
        $admin = User::find($request->id);

        return response()->json($admin);
    }
}
