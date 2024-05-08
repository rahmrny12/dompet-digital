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
                <a href="{{ route('students.index', $student->classroom_id) }}" class="btn btn-default btn-sm"><i
                        class='nav-icon fas fa-arrow-left'></i> &nbsp; Kembali</a>
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
                        <h5 class="card-title card-text mb-2 text-truncate" style="max-width: 500px;">Nama :
                            {{ $student->name }}</h5>
                        <h5 class="card-title card-text mb-2">No. Induk Siswa Nasional : {{ $student->nisn }}</h5>
                        <h5 class="card-title card-text mb-2">Kelas : {{ $student->classroom->name }}</h5>
                        @if ($student->gender == 'L')
                            <h5 class="card-title card-text mb-2">Jenis Kelamin : Laki-laki</h5>
                        @else
                            <h5 class="card-title card-text mb-2">Jenis Kelamin : Perempuan</h5>
                        @endif
                        <h5 class="card-title card-text mb-2">Tempat Lahir : {{ $student->birthplace ?? '-' }}</h5>
                        <h5 class="card-title card-text mb-2">Tanggal Lahir :
                            {{ $student->birthdate != null ? date('l, d F Y', strtotime($student->birthdate)) : '-' }}</h5>
                    </div>
                    <div class="col-md-5">
                        <div class="d-flex flex-column align-items-end">
                            {!! QrCode::size(256)->generate($student->nisn) !!}
                            {{--
                            <a href="{{ route('students.qr-code', $student->id) }}" class="btn btn-info btn-sm mt-4 mb-2 px-4">
                                <i class="nav-icon fas fa-qrcode"></i> &nbsp; Download
                            </a> --}}
                            <button type="button" class="btn btn-success btn-sm my-4 px-4"
                                onclick="getBalanceSetting({{ $student->id }})" data-toggle="modal"
                                data-target="#form-setting-card">
                                <i class="nav-icon fas fa-edit"></i> &nbsp; Atur Batas Limit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-md" id="form-setting-card" tabindex="-1" role="dialog"
        aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="judul">Aturan Batasan Limit</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('students.update-setting') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" value="{{ $student->id }}" id="id" name="id">
                                <div class="form-group" id="form_daily_limit">
                                    <label for="daily_limit">Batas Transaksi Harian</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp.</span>
                                        </div>
                                        <input type="text" class="form-control" placeholder="Batas Transaksi Harian"
                                            id="daily_limit" name="daily_limit">
                                    </div>
                                </div>
                                <div class="form-group" id="form_max_limit">
                                    <label for="max_limit">Batas Akumulasi Maksimal</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp.</span>
                                        </div>
                                        <input type="text" class="form-control" placeholder="Batas Akumulasi Maksimal"
                                            id="max_limit" name="max_limit">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal"><i
                                    class='nav-icon fas fa-arrow-left'></i> &nbsp; Kembali</button>
                            <button type="submit" class="btn btn-primary"><i class="nav-icon fas fa-save"></i> &nbsp;
                                Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        @endsection
        @section('script')
            <script>
                function getBalanceSetting(id) {
                    $.ajax({
                        type: "GET",
                        dataType: "JSON",
                        url: `{{ url('/students/${id}/setting') }}`,
                        success: function(result) {
                            if (result) {
                                $('#daily_limit').val(result.balance_setting.daily_limit);
                                $('#max_limit').val(result.balance_setting.max_limit);
                            }
                        },
                        error: function(err) {
                            console.log(err)
                        },
                        complete: function() {}
                    });
                }

                $("#MasterData").addClass("active");
                $("#liMasterData").addClass("menu-open");
                $("#DataStudents").addClass("active");
            </script>
        @endsection
