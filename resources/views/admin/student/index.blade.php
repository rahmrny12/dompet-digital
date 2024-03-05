@extends('template_backend.home')
@section('heading', 'Data Siswa')
@section('page')
    <li class="breadcrumb-item active">Data Siswa Kelas {{ $selected_classroom->name }}</li>
@endsection
@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <button type="button" class="btn btn-primary btn-sm" onclick="getCreate()" data-toggle="modal"
                        data-target="#form-students">
                        <i class="nav-icon fas fa-folder-plus"></i> &nbsp; Tambah Data Siswa
                    </button>
                </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>NISN</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $key => $data)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $data->nisn }}</td>
                                <td>{{ $data->name }}</td>
                                <td>{{ $data->classroom->name }}</td>
                                <td>
                                    <form action="{{ route('students.destroy', $data->id) }}" method="post">
                                        @csrf
                                        @method('delete')
                                        <button type="button" class="btn btn-success btn-sm"
                                            onclick="getEdit({{ $data->id }})" data-toggle="modal"
                                            data-target="#form-students">
                                            <i class="nav-icon fas fa-edit"></i> &nbsp; Edit
                                        </button>
                                        <a class="btn btn-info btn-sm text-white"
                                            href="{{ route('students.show', Crypt::encrypt($data->id)) }}">
                                            <i class="nav-icon fas fa-money-check"></i> &nbsp; Detail
                                        </a>
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
    <div class="modal fade bd-example-modal-md" id="form-students" tabindex="-1" role="dialog"
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
                    <form action="{{ route('students.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" id="id" name="id">
                                <div class="row">
                                    <div class="col-md-6 form-group" id="form_nisn">
                                        <label for="nisn">NISN</label>
                                        <input type='text' id="nisn" name='nisn'
                                            class="form-control @error('nisn') is-invalid @enderror"
                                            placeholder="{{ __('NISN') }}">
                                    </div>
                                    <input type="hidden" name="classroom_id" value="{{ $selected_classroom->id }}">
                                    <div class="col-md-6 form-group" id="form_nisn">
                                        <label>Kelas</label>
                                        <input type='text' class="form-control"
                                            placeholder="{{ __('Kelas') }}" value="{{ $selected_classroom->name }}"
                                            readonly>
                                    </div>
                                </div>
                                <div class="form-group" id="form_name">
                                    <label for="name">Nama Siswa</label>
                                    <input type='text' id="name" name='name' value="{{ old('name') }}"
                                        class="form-control @error('name') is-invalid @enderror"
                                        placeholder="{{ __('Nama Siswa') }}">
                                </div>
                                <div class="form-group">
                                    <label for="gender">Gender</label>
                                    <select id="gender" name="gender"
                                        class="form-control @error('gender') is-invalid @enderror">
                                        <option value="">-- Pilih Gender --</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group" id="form_birthplace">
                                        <label for="birthplace">Tempat Lahir</label>
                                        <input type='text' id="birthplace" name='birthplace'
                                            value="{{ old('birthplace') }}"
                                            class="form-control @error('birthplace') is-invalid @enderror"
                                            placeholder="{{ __('Tempat Lahir') }}">
                                    </div>
                                    <div class="col-md-6 form-group" id="form_birthdate">
                                        <label for="birthdate">Tanggal Lahir</label>
                                        <input type='date' id="birthdate" name='birthdate'
                                            value="{{ old('birthdate') }}"
                                            class="form-control @error('birthdate') is-invalid @enderror"
                                            placeholder="{{ __('Tanggal Lahir') }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="parent_id">Wali Murid</label>
                                    <select id="parent_id" name="parent_id"
                                        class="select2bs4 form-control @error('parent_id') is-invalid @enderror">
                                        <option value="">-- Pilih Wali Murid --</option>
                                        @foreach ($parents as $data)
                                            <option value="{{ $data->id }}"
                                                {{ $selected_classroom->id == $data->id ? 'selected' : null }}>
                                                {{ $data->name }}</option>
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
        function getCreate() {
            $("#judul").text('Tambah Data Siswa');
            $('#id').val('');
            $('#nisn').val("{{ old('nisn') }}");
            $('#name').val("{{ old('name') }}");
            $(`#gender`).val("{{ old('gender') }}");
            $('#classroom_id').val("{{ old('classroom_id') }}");
            $(`#parent_id`).val("{{ old('parent_id') }}").trigger('change');
        }

        function getEdit(id) {
            $.ajax({
                type: "GET",
                dataType: "JSON",
                url: `{{ url('/students/${id}/json') }}`,
                success: function(result) {
                    if (result) {
                        $("#judul").text('Edit Data Siswa ' + result.name);
                        $('#id').val(result.id);
                        $('#nisn').val(result.nisn);
                        $('#name').val(result.name);
                        $('#classroom_id').val(result.classroom_id);
                        $('#gender').val(result.gender);
                        $('#birthplace').val(result.birthplace);
                        $('#birthdate').val(result.birthdate);
                        $(`#parent_id`).val(result.parent_id).trigger('change');
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
        $("#DataStudents").addClass("active");
    </script>
@endsection
