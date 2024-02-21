@extends('template_backend.home')
@section('heading', 'Detail Siswa')
@section('page')
  <li class="breadcrumb-item active"><a href="{{ route('students.index', $student->classroom_id) }}">Siswa</a></li>
  <li class="breadcrumb-item active">Detail Siswa</li>
@endsection
@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <a href="{{ route('students.index', $student->classroom_id) }}" class="btn btn-default btn-sm"><i class='nav-icon fas fa-arrow-left'></i> &nbsp; Kembali</a>
        </div>
        <div class="card-body">
            <div class="row no-gutters ml-2 mb-2 mr-2">
                @if ($student->foto)
                    <div class="col-md-4">
                            <img src="{{ asset($student->foto) }}" class="card-img img-details" alt="...">
                    </div>
                    <div class="col-md-1 mb-4"></div>
                @endif
                <div class="col-md-7">
                    <h5 class="card-title card-text mb-2">Nama : {{ $student->name }}</h5>
                    <h5 class="card-title card-text mb-2">No. Induk Siswa Nasional : {{ $student->nisn }}</h5>
                    <h5 class="card-title card-text mb-2">Kelas : {{ $student->classroom->name }}</h5>
                    @if ($student->gender == 'L')
                        <h5 class="card-title card-text mb-2">Jenis Kelamin : Laki-laki</h5>
                    @else
                        <h5 class="card-title card-text mb-2">Jenis Kelamin : Perempuan</h5>
                    @endif
                    <h5 class="card-title card-text mb-2">Tempat Lahir : {{ $student->birthplace ?? '-' }}</h5>
                    <h5 class="card-title card-text mb-2">Tanggal Lahir : {{ $student->birthdate != null ? date('l, d F Y', strtotime($student->birthdate)) : '-' }}</h5>
                </div>
                <div class="col-md-5">
                    <div class="position-relative">
                        <img class="position-absolute" src="{{ asset('img/card.png') }}" width="400px" alt="">
                        <div class="position-absolute w-100 h-100 translate-middle-y text-center">
                            <h3 class="font-weight-bold my-4">{{ $student->name }}</h3>
                            <h5>Saldo Kartu</h5>
                            <h5 class="font-weight-bold">Rp. -</h5>
                            <h5>NFC ID</h5>
                            <h5>-</h5>
                        </div>
                    </div>
                    button
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
    <script>
        $("#MasterData").addClass("active");
        $("#liMasterData").addClass("menu-open");
        $("#DataSiswa").addClass("active");