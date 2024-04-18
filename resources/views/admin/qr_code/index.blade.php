@extends('template_backend.home')
@section('heading', 'Cetak QR Code Siswa')
@section('page')
    <li class="breadcrumb-item active">Data QR Code Siswa Kelas {{ $selected_classroom->name }}</li>
@endsection
@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <a href="{{ route('students.qr-code.classrooms', $selected_classroom->id) }}" class="btn btn-secondary btn-sm">
                    <i class="nav-icon fas fa-arrow-left"></i> &nbsp; Kembali
                </a>
                <a href="{{ route('students.qr-code.all.print', $selected_classroom->id) }}" class="btn btn-primary btn-sm float-right" target="_blank">
                    <i class="nav-icon fas fa-print"></i> &nbsp; Cetak
                </a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>NISN</th>
                            <th>Nama Siswa</th>
                            <th>QR Code</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $key => $data)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $data->nisn }}</td>
                                <td>{{ $data->name }}</td>
                                <td>
                                    {!! QrCode::size(128)->generate($data->nisn) !!}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
@endsection

@section('script')
  <script>
    $("#MasterData").addClass("active");
    $("#liMasterData").addClass("menu-open");
    $("#DataQrCode").addClass("active");
  </script>
@endsection
