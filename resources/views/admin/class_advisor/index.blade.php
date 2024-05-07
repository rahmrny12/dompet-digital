@extends('template_backend.home')
@section('heading', 'Data Wali Kelas')
@section('page')
    <li class="breadcrumb-item active">Data Wali Kelas</li>
@endsection
@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <button type="button" class="btn btn-primary btn-sm" onclick="getCreate()" data-toggle="modal"
                        data-target="#form-teachers">
                        <i class="nav-icon fas fa-folder-plus"></i> &nbsp; Tambah Data Wali Kelas
                    </button>
                </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>NUPK</th>
                            <th>Nama Wali Kelas</th>
                            <th>Kelas</th>
                            <th>Jenis Kelamin</th>
                            <th>Nomor Telepon</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($class_advisors as $key => $data)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $data->nip }}</td>
                                <td>{{ $data->name }}</td>
                                <td>{{ $data->classroom->name ?? '-' }}</td>
                                <td>{{ $data->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                <td>{{ $data->phone }}</td>
                                <td>
                                    <form action="{{ route('class-advisors.destroy', $data->id) }}" method="post">
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
                    <form action="{{ route('class-advisors.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" id="id" name="id">
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
                                <div class="form-group">
                                    <label for="classroom_id">Kelas</label>
                                    <select id="classroom_id" name="classroom_id"
                                        class="select2bs4 form-control @error('classroom_id') is-invalid @enderror">
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach ($classrooms as $data)
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
        var classroomsData = @json($classrooms);

        function getCreate() {
            $("#judul").text('Tambah Data Wali Kelas');
            $('#id').val('');
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

            $('#classroom_id').empty();
            $.each(classroomsData, function(index, el) {
                if (!el.teacher) {
                    $('#classroom_id').append($('<option>', {
                        value: el.id,
                        text: el.name
                    }));
                }
            });
            $('#classroom_id option').removeAttr("selected").trigger('change');
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

            $('#classroom_id option').removeAttr("selected").trigger('change');
            $('#classroom_id').empty();
            $.each(classroomsData, function(index, el) {
                $('#classroom_id').append($('<option>', {
                    value: el.id,
                    text: el.name
                }));
            });

            $.ajax({
                type: "GET",
                dataType: "JSON",
                url: `{{ url('/class-advisors/${id}/json') }}`,
                success: function(result) {
                    if (result) {
                        $("#judul").text('Edit Data Wali Kelas');
                        $('#id').val(result.id);
                        console.log(result.teacher_id)
                        console.log($(`#teacher_id option[value=${result.teacher_id}]`))
                        $(`#teacher_id option[value=${result.teacher_id}]`).attr('selected', 'selected')
                            .trigger('change');
                        $(`#classroom_id option[value=${result.classroom_id}]`).attr('selected', 'selected')
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
        $("#DataClassAdvisors").addClass("active");
    </script>
@endsection
