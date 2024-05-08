<?php

namespace App\Imports;

use App\Models\Teacher;
use App\Models\Classroom;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class TeacherImport implements ToModel, WithStartRow
{
    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }

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
