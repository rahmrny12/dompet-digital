<?php

namespace App\Imports;

use App\Models\StudentParent;
use App\Models\User;
use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class TeacherImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Teacher([
            'nip' => $row[0],
            'name' => $row[1],
            'gender' => $row[2],
            'phone' => $row[3],
        ]);
    }
}
