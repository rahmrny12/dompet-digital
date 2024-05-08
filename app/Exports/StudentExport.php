<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentExport implements FromCollection, ShouldAutoSize, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $students = Student::select(
            'students.nisn',
            'students.name',
            'students.gender',
            'students.birthplace',
            'students.birthdate',
            'classrooms.name as classroom_name',
            'users.username as parent_username',
            'users.real_password as parent_password',
            'student_parents.name as parent_name',
        )
            ->join('classrooms', 'classrooms.id', 'students.classroom_id')
            ->join('student_parents', 'student_parents.id', 'students.parent_id')
            ->join('users', 'student_parents.user_id', 'users.id')
            ->get();

        return $students;
    }

    public function headings(): array
    {
        return [
            'NISN',
            'Nama Siswa',
            'Jenis Kelamin (L/P)',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Nama Kelas',
            'Username Wali Murid',
            'Kata Sandi Wali Murid',
            'Nama Wali Murid',
        ];
    }
}
