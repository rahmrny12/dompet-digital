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
                    <a href="{{ route('students.export-excel') }}" class="btn btn-success btn-sm my-3" target="_blank"><i
                            class="nav-icon fas fa-file-export"></i> &nbsp; EXPORT EXCEL</a>
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
                                            <i class="nav-icon fas fa-eye"></i> &nbsp;Detail students</a>
                                        <button type="button" class="btn btn-secondary btn-sm"
                                            onclick="getEditStudents({{ $data->id }})" data-toggle="modal"
                                            data-target=".modal-change-class">
                                            <i class="nav-icon fas fa-users"></i> &nbsp; Pindah Kelas
                                        </button>
                                        <button type="button" class="btn btn-success btn-sm"
                                            onclick="getEditTeacher({{ $data->id }})" data-toggle="modal"
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
                                    <li>rows 2 = Nama students</li>
                                    <li>rows 3 = Jenis Kelamin (L/P)</li>
                                    <li>rows 4 = Tempat Lahir</li>
                                    <li>rows 5 = Tanggal Lahir</li>
                                    <li>rows 6 = Nama Kelas</li>
                                    <li>rows 7 = Username Wali Murid</li>
                                    <li>rows 8 = Kata Sandi Wali Murid</li>
                                    <li>rows 9 = Nama Wali Murid</li>
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

    <!-- Modal pindah kelas -->
    <div class="modal fade bd-example-modal-lg modal-change-class" tabindex="-1" role="dialog"
        aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="judul-students">Pindah Kelas</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('students.change-class') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="d-flex align-items-center">
                                            <span>Kelas asal : </span>
                                            <span class="mx-3 font-weight-bold" id="from_classroom">-</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <label class="m-0 p-0 mr-3 font-weight-normal" for="classroom_id">Pindah ke kelas
                                                : </label>
                                            <select id="classroom_id" name="classroom_id"
                                                class="select2bs4 form-control @error('classroom_id') is-invalid @enderror">
                                                <option value="">-- Pilih Kelas Tujuan --</option>
                                                @foreach ($classrooms as $data)
                                                    <option value="{{ $data->id }}">{{ $data->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <table class="table table-bordered table-striped table-hover" width="100%">
                                        <thead>
                                            <tr>
                                                <th>No Induk Siswa</th>
                                                <th>Nama Siswa</th>
                                                <th>L/P</th>
                                                <th>Pilih Siswa</th>
                                            </tr>
                                        </thead>
                                        <tbody id="data-students">
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.col -->
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal"><i
                                    class="nav-icon fas fa-arrow-left"></i> &nbsp; Kembali</button>
                            <button type="submit" class="btn btn-primary"><i class="nav-icon fas fa-save"></i> &nbsp;
                                Pindah Kelas</button>
                        </div>
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

        function getEditTeacher(id) {
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

        function getEditStudents(id) {
            $.ajax({
                type: "GET",
                data: "id=" + id,
                dataType: "JSON",
                url: "{{ url('/students/all/json') }}",
                success: function(result) {
                    if (result) {
                        var students = "";
                        $.each(result, function(index, val) {
                            $('#from_classroom').text(val.classroom);
                            students += "<tr>";
                            students += "<td>" + val.nisn + "</td>";
                            students += "<td>" + val.name + "</td>";
                            students += "<td>" + val.gender + "</td>";
                            students += `<td>
                                <input type="checkbox" name="student_id[` + index + `]" value="` + val.id + `" checked>
                                </td>
                            `;
                            students += "</tr>";
                        });

                        $("#data-students").html(students);
                    }
                },
                error: function() {
                    $("#data-students").html('');
                    toastr.error("Error 400!");
                },
            });
        }

        $("#MasterData").addClass("active");
        $("#liMasterData").addClass("menu-open");
        $("#DataClassrooms").addClass("active");
    </script>
@endsection
