@extends('template_backend.home')
@section('heading', 'Data Kelas')
@section('page')
    <li class="breadcrumb-item active">Data Kelas</li>
@endsection
@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <button type="button" class="btn btn-primary btn-sm" onclick="getCreate()" data-toggle="modal"
                        data-target="#form-classrooms">
                        <i class="nav-icon fas fa-folder-plus"></i> &nbsp; Tambah Data Kelas
                    </button>
                    <a href="{{ route('students.export-excel') }}" class="btn btn-success btn-sm my-3"
                        target="_blank"><i class="nav-icon fas fa-file-export"></i> &nbsp; EXPORT EXCEL</a>
                    <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal"
                        data-target="#importStudentExcel">
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
                            <th>Nama</th>
                            <th>Wali Kelas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($classrooms as $key => $data)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $data->name }}</td>
                                <td>{{ $data->teacher->name ?? '-' }}</td>
                                <td>
                                    <form action="{{ route('classrooms.destroy', $data->id) }}" method="post">
                                        @csrf
                                        @method('delete')
                                        <a href="{{ url('classrooms/' . $data->id . '/students') }}"
                                            class="btn btn-info text-light btn-sm">
                                            <i class="nav-icon fas fa-eye"></i> &nbsp;Detail Siswa</a>
                                        <button type="button" class="btn btn-success btn-sm"
                                            onclick="getEdit({{ $data->id }})" data-toggle="modal"
                                            data-target="#form-classrooms">
                                            <i class="nav-icon fas fa-edit"></i> &nbsp; Edit Wali Kelas
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
    <div class="modal fade" id="importStudentExcel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="post" action="{{ route('students.import-excel') }}" enctype="multipart/form-data">
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
                                    <li>rows 1 = NISN</li>
                                    <li>rows 2 = Jenis Kelamin (L/P)</li>
                                    <li>rows 3 = Tempat Lahir</li>
                                    <li>rows 4 = Tanggal Lahir</li>
                                    <li>rows 5 = Nama Kelas</li>
                                    <li>rows 6 = Username Wali Murid</li>
                                    <li>rows 7 = Kata Sandi Wali Murid</li>
                                    <li>rows 8 = Nama Wali Murid</li>
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
    <div class="modal fade bd-example-modal-md" id="form-classrooms" tabindex="-1" role="dialog"
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
                    <form action="{{ route('classrooms.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" id="id" name="id">
                                <div class="form-group" id="form_name">
                                    <label for="name">Nama Kelas</label>
                                    <input type='text' id="name" name='name'
                                        class="form-control @error('name') is-invalid @enderror"
                                        placeholder="{{ __('Nama Kelas') }}">
                                </div>
                                <div class="form-group">
                                    <label for="teacher_id">Wali Kelas</label>
                                    <select id="teacher_id" name="teacher_id"
                                        class="select2bs4 form-control @error('teacher_id') is-invalid @enderror">
                                        <option value="">-- Pilih Wali Kelas --</option>
                                        @foreach ($teachers as $data)
                                            <option value="{{ $data->id }}">{{ $data->name }}</option>
                                        @endforeach
                                    </select>
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
        var teachersData = @json($teachers);

        function getCreate() {
            $("#judul").text('Tambah Data Kelas');
            $('#id').val('');
            $('#name').val("{{ old('name') }}");
            $('#teacher_id').empty();
            $.each(teachersData, function(index, el) {
                if (!el.classroom) {
                    $('#teacher_id').append($('<option>', {
                        value: el.id,
                        text: el.name
                    }));
                }
            });
            $('#teacher_id option').removeAttr("selected").trigger('change');
        }

        function getEdit(id) {
            $('#teacher_id option').removeAttr("selected").trigger('change');
            $('#teacher_id').empty();
            $.each(teachersData, function(index, el) {
                $('#teacher_id').append($('<option>', {
                    value: el.id,
                    text: el.name
                }));
            });

            $.ajax({
                type: "GET",
                dataType: "JSON",
                url: `{{ url('/classrooms/${id}/json') }}`,
                success: function(result) {
                    if (result) {
                        $("#judul").text('Edit Data Kelas ' + result.name);
                        $('#id').val(result.id);
                        $('#name').val(result.name);
                        $(`#teacher_id option[value=${result.teacher_id}]`).attr('selected', 'selected')
                            .trigger('change');
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
        $("#DataClassrooms").addClass("active");
    </script>
@endsection
