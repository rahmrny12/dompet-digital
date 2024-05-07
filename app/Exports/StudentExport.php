<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentExport implements FromCollection, ShouldAutoSize, WithColumnFormatting, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $students = Student
        ::limit(4)->get();
        dd($students);
        return $students;
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Jam Masuk',
            'Jam Pulang',
            'Status Masuk',
            'Nama Guru',
            'NIP',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_TEXT,
        ];
    }
}
