<?php

namespace App\Imports;

use App\Models\StudentParent;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class ParentImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $user = User::create([
            'name' => $row[0],
            'username' => $row[1],
            'password' => Hash::make($row[2]),
            'real_password' => $row[2],
            'role' => 'parent',
        ]);

        return new StudentParent([
            'name' => $row[0],
            'user_id' => $user->id,
        ]);
    }
}
