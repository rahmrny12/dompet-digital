<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\StudentParent;
use App\Models\Classroom;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Exception;
use Maatwebsite\Excel\Concerns\WithStartRow;

class StudentImport implements ToModel, WithStartRow
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
        $user = User::where('username', $row[6])->exists();
        if ($user) {
            $parent = StudentParent::join('users', 'users.id', 'student_parents.user_id')->where('users.username', $row[6])->first();
        } else {
            $user = User::create([
                'name' => $row[8] ?? $row[6],
                'username' => $row[6],
                'password' => Hash::make($row[7]),
                'real_password' => $row[7],
                'role' => 'parent',
            ]);

            $parent = StudentParent::create([
                'user_id' => $user->id,
                'name' => $row[8] ?? $row[6],
            ]);
        }

        /* -------------- */

        $classroom = Classroom::where('name', $row[5])->first();

        $monthNames = [
            'Januari' => 'January',
            'Februari' => 'February',
            'Maret' => 'March',
            'April' => 'April',
            'Mei' => 'May',
            'Juni' => 'June',
            'Juli' => 'July',
            'Agustus' => 'August',
            'September' => 'September',
            'Oktober' => 'October',
            'November' => 'November',
            'Desember' => 'December'
        ];

        $dateString = $row[4];
        foreach ($monthNames as $indonesian => $english) {
            $dateString = str_replace($indonesian, $english, $dateString);
        }

        try {
            $date = Carbon::parse($dateString)->format('Y-m-d');
        } catch (Exception $e) {
            $date = null;
        }

        return new Student([
            'nisn' => $row[0],
            'name' => $row[1],
            'gender' => $row[2],
            'birthplace' => $row[3],
            'birthdate' => $date,
            'classroom_id' => $classroom->id ?? null,
            'parent_id' => $parent->id,
        ]);
    }
}
