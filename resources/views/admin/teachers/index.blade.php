@extends('template_backend.home')
@section('heading', 'Data Guru')
@section('page')
    <li class="breadcrumb-item active">Data Guru</li>
@endsection
@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <button type="button" class="btn btn-primary btn-sm" onclick="getCreate()" data-toggle="modal"
                        data-target="#form-teachers">
                        <i class="nav-icon fas fa-folder-plus"></i> &nbsp; Tambah Data Guru
                    </button>
                    <a href="{{ route('teachers.export-excel') }}" class="btn btn-success btn-sm my-3"
                        target="_blank"><i class="nav-icon fas fa-file-export"></i> &nbsp; EXPORT EXCEL</a>
                    <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal"
                        data-target="#importTeacherExcel">
                        <i class="nav-icon fas fa-file-import"></i> &nbsp; IMPORT EXCEL
                    </button>
                </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>NUPTK</th>
                            <th>Nama Guru</th>
                            <th>Kelas</th>
                            <th>Jenis Kelamin</th>
                            <th>Nomor Telepon</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($teachers as $key => $data)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $data->nip }}</td>
                                <td>{{ $data->name }}</td>
                                <td>{{ $data->classroom->name ?? '-' }}</td>
                                <td>{{ $data->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                <td>{{ $data->phone }}</td>
                                <td>
                                    <form action="{{ route('teachers.destroy', $data->id) }}" method="post">
                                        @csrf
                                        @method('delete')
                                        <button type="button" class="btn btn-success btn-sm"
                                            onclick="getEdit({{ $data->id }})" data-toggle="modal"
                                            data-target="#form-teachers">
                                            <i class="nav-icon fas fa-edit"></i> &nbsp; Edit
                                        </button>
                                        <button class="btn btn-danger btn-sm"><i class="nav-icon fas fa-trash-alt"></i>
                                            &nbsp; Hapus</button>
                                    </form>
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

    {{-- Import Excel Modal --}}
    <div class="modal fade" id="importTeacherExcel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="post" action="{{ route('teachers.import-excel') }}" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Import Excel</h5>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                <h5 class="modal-title">Petunjuk :</h5>
                            </div>
                            <div class="card-body">
                                <ul>
                                    <li>rows 1 = NUPK</li>
                                    <li>rows 2 = Nama</li>
                                    <li>rows 3 = Jenis Kelamin (L/P)</li>
                                    <li>rows 4 = Nomor Telepon</li>
                                </ul>
                            </div>
                        </div>
                        <label>Pilih file excel</label>
                        <div class="form-group">
                            <input type="file" name="file" required="required">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Extra large modal -->
    <div class="modal fade bd-example-modal-md" id="form-teachers" tabindex="-1" role="dialog"
        aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="judul"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('teachers.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" id="id" name="id">
                                <div class="form-group" id="form_nip">
                                    <label for="nip">NUPK</label>
                                    <input type='text' id="nip" name='nip'
                                        class="form-control @error('nip') is-invalid @enderror"
                                        placeholder="{{ __('NUPK') }}">
                                </div>
                                <div class="form-group" id="form_name">
                                    <label for="name">Nama Guru</label>
                                    <input type='text' id="name" name='name'
                                        class="form-control @error('name') is-invalid @enderror"
                                        placeholder="{{ __('Nama Guru') }}">
                                </div>
                                <div class="form-group">
                                    <label for="gender">Jenis Kelamin</label>
                                    <select id="gender" name="gender"
                                        class="form-control @error('gender') is-invalid @enderror">
                                        <option value="">-- Pilih Jenis Kelamin --</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>
                                <div class="form-group" id="form_phone">
                                    <label for="phone">Nomor Telepon</label>
                                    <input type='text' id="phone" name='phone'
                                        class="form-control @error('phone') is-invalid @enderror"
                                        placeholder="{{ __('Nomor Telepon') }}">
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i
                            class='nav-icon fas fa-arrow-left'></i> &nbsp; Kembali</button>
                    <button type="submit" class="btn btn-primary"><i class="nav-icon fas fa-save"></i> &nbsp;
                        Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        function getCreate() {
            $("#judul").text('Tambah Data Guru');
            $('#id').val('');
            $('#nip').val("{{ old('nip') }}");
            $('#name').val("{{ old('name') }}");
            $('#gender').val("{{ old('gender') }}");
            $('#phone').val("{{ old('phone') }}");
        }

        function getEdit(id) {
            $.ajax({
                type: "GET",
                dataType: "JSON",
                url: `{{ url('/teachers/${id}/json') }}`,
                success: function(result) {
                    if (result) {
                        $("#judul").text('Edit Data Guru ' + result.name);
                        $('#id').val(result.id);
                        $('#nip').val(result.nip);
                        $('#name').val(result.name);
                        $('#gender').val(result.gender);
                        $('#phone').val(result.phone);
                    }
                },
                error: function() {
                    toastr.error("Errors 404!");
                },
                complete: function() {}
            });
        }

        $("#MasterData").addClass("active");
        $("#liMasterData").addClass("menu-open");
        $("#DataTeachers").addClass("active");
    </script>
@endsection
