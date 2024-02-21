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
                            <th>NIP</th>
                            <th>Nama Wali Kelas</th>
                            <th>Email</th>
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
                                <td>{{ $data->email }}</td>
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
                                    <label for="nip">NIP</label>
                                    <input type='text' id="nip" name='nip'
                                        class="form-control @error('nip') is-invalid @enderror"
                                        placeholder="{{ __('NIP') }}">
                                </div>
                                <div class="form-group" id="form_name">
                                    <label for="name">Nama Wali Kelas</label>
                                    <input type='text' id="name" name='name'
                                        class="form-control @error('name') is-invalid @enderror"
                                        placeholder="{{ __('Nama Wali Kelas') }}">
                                </div>
                                <div class="form-group" id="form_email">
                                    <label for="email">Email</label>
                                    <input type='text' id="email" name='email'
                                        class="form-control @error('email') is-invalid @enderror"
                                        placeholder="{{ __('Email') }}">
                                </div>
                                <div class="form-group">
                                    <label for="gender">Jenis Kelamin</label>
                                    <select id="gender" name="gender" class="form-control @error('gender') is-invalid @enderror">
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
                        Tambahkan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        function getCreate() {
            $("#judul").text('Tambah Data Wali Kelas');
            $('#id').val('');
            $('#nip').val('');
            $('#name').val('');
            $('#email').val('');
            $('#gender').val('');
            $('#phone').val('');
        }

        function getEdit(id) {
            $.ajax({
                type: "GET",
                dataType: "JSON",
                url: `{{ url('/teachers/${id}/json') }}`,
                success: function(result) {
                    if (result) {
                        $("#judul").text('Edit Data Wali Kelas ' + result.name);
                        $('#id').val(result.id);
                        $('#nip').val(result.nip);
                        $('#name').val(result.name);
                        $('#email').val(result.email);
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
