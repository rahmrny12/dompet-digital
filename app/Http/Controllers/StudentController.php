<?php

namespace App\Http\Controllers;

use App\Models\BalanceSetting;
use App\Models\Student;
use App\Models\Classroom;
use App\Models\Transaction;
use App\Models\StudentParent;
use App\Models\RechargeHistory;
use App\Models\StudentBalance;
use App\Imports\StudentImport;
use App\Exports\StudentExport;
use Crypt;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class StudentController extends Controller
{
    public function index($id)
    {
        $selected_classroom = Classroom::find($id);

        $students = Student::where('classroom_id', $id)->get();
        $classrooms = Classroom::get();
        $parents = StudentParent::get();
        return view('admin.students.index', compact('students', 'classrooms', 'parents', 'selected_classroom'));
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
            'nisn' => "required|unique:students,nisn,$request->id",
            'name' => 'required',
            'classroom_id' => 'required',
            'gender' => 'required',
            // 'birthplace' => 'required',
            // 'birthdate' => 'required',
            // 'nfc_id' => 'required',
        ]);

        Student::updateOrCreate(
            [
                'id' => $request->id
            ],
            [
                'nisn' => $request->nisn,
                'name' => $request->name,
                'classroom_id' => $request->classroom_id,
                'gender' => $request->gender,
                'birthplace' => $request->birthplace,
                'birthdate' => $request->birthdate,
                'parent_id' => $request->parent_id,
                // 'nfc_id' => $request->nfc_id,
            ]
        );

        return redirect()->back()->with('success', 'Data siswa berhasil diperbarui!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id = Crypt::decrypt($id);
        $student = Student::with('balance')->find($id);
        return view('admin.students.show', compact('student'));
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
        $existTransaction = Transaction::where('student_id', $id)->exists();
        if ($existTransaction) {
            return redirect()->back()->with('warning', 'Siswa ini sudah pernah melakukan transaksi');
        }

        $result = Student::findorfail($id)->delete();
        if ($result) {
            return redirect()->back()->with('warning', 'Data siswa berhasil dihapus! (Silahkan cek trash data siswa)');
        }

        return redirect()->back()->with('danger', 'Data siswa gagal dihapus.');
    }

    public function trash()
    {
        $students = Student::onlyTrashed()->get();
        return view('admin.students.trash', compact('students'));
    }

    public function restore($id)
    {
        $id = Crypt::decrypt($id);
        $result = Student::withTrashed()->findorfail($id)->restore();
        if ($result) {
            return redirect()->back()->with('info', 'Data siswa berhasil direstore! (Silahkan cek data siswa)');
        }

        return redirect()->back()->with('warning', 'Data siswa gagal direstore.');
    }

    public function kill($id)
    {
        $student = Student::withTrashed()->findorfail($id);
        RechargeHistory::where('student_id', $student->id)->delete();
        StudentBalance::where('student_id', $student->id)->delete();
        $result = $student->forceDelete();
        if ($result) {
            return redirect()->back()->with('success', 'Data siswa berhasil dihapus secara permanen');
        }

        return redirect()->back()->with('warning', 'Data siswa gagal dihapus secara permanen');
    }

    public function getEdit($id)
    {
        $students = Student::select(
            'id',
            'nisn',
            'name',
            'classroom_id',
            'gender',
            'birthplace',
            'birthdate',
            'parent_id',
            'nfc_id',
        )->where('id', $id)->first();
        return response()->json($students);
    }

    public function getBalanceSetting($id)
    {
        $student = Student::with('balance_setting')->find($id);
        return response()->json($student);
    }

    public function updateBalanceSetting(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'daily_limit' => 'nullable',
            'max_limit' => 'nullable',
        ]);

        BalanceSetting::updateOrCreate(
            [
                'student_id' => $request->id
            ],
            [
                'daily_limit' => $request->daily_limit ?? 0,
                'max_limit' => $request->max_limit ?? 0,
            ]
        );

        return redirect()->back()->with('success', 'Data siswa berhasil diperbarui!');
    }

    public function getStudentsByClassroom($id)
    {
        $students = Student::with('balance')->where('classroom_id', $id)->get();
        return response()->json($students);
    }

    public function qrCode($id)
    {
        $student = Student::find($id);

        return response()->streamDownload(
            function () use ($student) {
                echo QrCode::size(200)
                    ->format('png')
                    ->generate($student->nisn);
            },
            'qr-code.png',
            [
                'Content-Type' => 'image/png',
            ]
        );

    }

    public function qrCodeStudentByClassroom()
    {
        $classrooms = Classroom::get();
        return view('admin.qr_code.classrooms', compact('classrooms'));
    }

    public function qrCodeAll($id)
    {
        $selected_classroom = Classroom::find($id);

        $students = Student::where('classroom_id', $id)->get();
        $classrooms = Classroom::get();
        $parents = StudentParent::get();
        return view('admin.qr_code.index', compact('students', 'selected_classroom'));
    }

    public function printQrCodeAll($id)
    {
        $selected_classroom = Classroom::find($id);

        $students = Student::where('classroom_id', $id)->get();
        $classrooms = Classroom::get();
        $parents = StudentParent::get();
        return view('admin.qr_code.print_qr_code', compact('students', 'selected_classroom'));
    }

    public function importExcel(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);
        $file = $request->file('file');
        $nama_file = rand() . $file->getClientOriginalName();
        $file->move('excel_file/student', $nama_file);
        Excel::import(new StudentImport, public_path('/excel_file/student/' . $nama_file));
        return redirect()->back()->with('success', 'Data Kelas Berhasil Diimport!');
    }

    public function exportExcel()
    {
        $date = Carbon::now()->toDateString();
        return Excel::download(new StudentExport(), "data-siswa-$date.xlsx");
    }

    public function changeClass(Request $request)
    {
        $this->validate($request, [
            'classroom_id' => 'required',
            'student_id' => 'required',
        ]);

        Student::whereIn('id', $request->student_id)->update([
            'classroom_id' => $request->classroom_id
        ]);

        return redirect()->back()->with('success', 'Data kelas untuk siswa berhasil diperbarui!');
    }

    public function getStudentsJson(Request $request)
    {
        $students = Student::select('students.id', 'classrooms.name as classroom', 'students.nisn', 'students.name', 'students.gender')
            ->join('classrooms', 'classrooms.id', 'students.classroom_id')
            ->OrderBy('name', 'asc')->where('classroom_id', $request->id)->get();


        return response()->json($students);
    }
}
