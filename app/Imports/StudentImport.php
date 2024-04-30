<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\StudentParent;
use App\Models\Classroom;
use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Exception;

class StudentImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $classroom = Classroom::where('name', $row[5])->first();
        $user = User::join('student_parents', 'users.id', 'student_parents.user_id')
            ->where('username', $row[6])->first();

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
            'parent_id' => $user->id,
        ]);
    }
}
