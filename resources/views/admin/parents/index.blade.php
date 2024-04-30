@extends('template_backend.home')
@section('heading', 'Data Wali Murid')
@section('page')
    <li class="breadcrumb-item active">Data Wali Murid</li>
@endsection
@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <button type="button" class="btn btn-primary btn-sm" onclick="getCreate()" data-toggle="modal"
                        data-target="#form-parents">
                        <i class="nav-icon fas fa-folder-plus"></i> &nbsp; Tambah Data Wali Murid
                    </button>
                </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Wali Murid</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($parents as $key => $data)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $data->name }}</td>
                                <td>
                                    <form action="{{ route('parents.destroy', $data->id) }}" method="post">
                                        @csrf
                                        @method('delete')
                                        <button type="button" class="btn btn-success btn-sm"
                                            onclick="getEdit({{ $data->id }})" data-toggle="modal"
                                            data-target="#form-parents">
                                            <i class="nav-icon fas fa-edit"></i> &nbsp; Edit
                                        </button>
                                        <button type="button" class="btn btn-primary btn-sm"
                                            onclick="getEditPassword({{ $data->id }})" data-toggle="modal"
                                            data-target="#form-edit-password">
                                            <i class="nav-icon fas fa-key"></i> &nbsp; Edit Password
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
    <div class="modal fade bd-example-modal-md" id="form-parents" tabindex="-1" role="dialog"
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
                    <form action="{{ route('parents.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" id="id" name="id">
                                <div class="form-group" id="form_name">
                                    <label for="name">Nama Wali Murid</label>
                                    <input type='text' id="name" name='name'
                                        class="form-control @error('name') is-invalid @enderror"
                                        placeholder="{{ __('Nama Wali Murid') }}">
                                </div>
                                <div class="form-group" id="form_email">
                                    <label for="email">Email</label>
                                    <input type='text' id="email" name='email'
                                        class="form-control @error('email') is-invalid @enderror"
                                        placeholder="{{ __('Email') }}">
                                </div>
                                <div class="form-group" id="form_username">
                                    <label for="username">Username</label>
                                    <input type='text' id="username" name='username'
                                        class="form-control @error('username') is-invalid @enderror"
                                        placeholder="{{ __('Username') }}">
                                </div>
                                <div class="form-group" id="form_phone">
                                    <label for="phone">Nomor Telepon</label>
                                    <input type='text' id="phone" name='phone'
                                        class="form-control @error('phone') is-invalid @enderror"
                                        placeholder="{{ __('Nomor Telepon') }}">
                                </div>
                                <div class="form-group">
                                    <label for="student_id">Siswa</label>
                                    <select id="student_id" name="student_id[]"
                                        class="select2bs4 form-control @error('student_id') is-invalid @enderror" multiple>
                                        @foreach ($students as $data)
                                            <option value="{{ $data->id }}">{{ $data->name }} -
                                                {{ $data->classroom->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group" id="form_address">
                                    <label for="address">Alamat (opsional)</label>
                                    <input type='text' id="address" name='address'
                                        class="form-control @error('address') is-invalid @enderror"
                                        placeholder="{{ __('Alamat wali murid') }}">
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

    <!-- Extra large modal -->
    <div class="modal fade bd-example-modal-md" id="form-edit-password" tabindex="-1" role="dialog"
        aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="judul">Edit Password</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('parents.update-password') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" id="id_reset_password" name="id">
                                <div id="real_password" class="mb-2">Password saat ini : -</div>
                                <div class="form-group" id="form_password">
                                    <label for="password">Ganti Password</label>
                                    <input type='password' id="password" name='password'
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="{{ __('Masukkan Password Baru') }}">
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
        var studentsData = @json($students);

        function getCreate() {
            $("#judul").text('Tambah Data Wali Murid');
            $('#id').val('');
            $('#nisn').val('');
            $('#name').val('');
            $('#email').val('');
            $('#phone').val('');
            $('#classroom_id').val('');
            $('#student_id option').removeAttr("selected").trigger('change');
            $('#student_id').empty();
            $.each(studentsData, function(index, el) {
                $('#student_id').append($('<option>', {
                    value: el.id,
                    text: el.name + ' - ' + el.classroom.name
                }));
            });
        }

        function getEdit(id) {
            $('#student_id option').removeAttr("selected").trigger('change');

            $.ajax({
                type: "GET",
                dataType: "JSON",
                url: `{{ url('/parents/${id}/json') }}`,
                success: function(result) {
                    if (result) {
                        $("#judul").text('Edit Data Wali Murid ' + result[0].name);
                        $("#id").val(result[0].id)
                        $("#name").val(result[0].name)
                        $("#email").val(result[0].email)
                        $("#username").val(result[0].user.username)
                        $("#phone").val(result[0].phone)
                        $("#address").val(result[0].address)

                        $('#student_id').empty();
                        $.each(result[1], function(index, el) {
                            $('#student_id').append($('<option>', {
                                value: el.id,
                                text: el.name + ' - ' + el.classroom.name
                            }));
                        });

                        $.each(result[0].students, function(index, el) {
                            $(`#student_id option[value=${el.id}]`).attr('selected', 'selected')
                                .trigger('change');
                        });
                    }
                },
                error: function() {
                    toastr.error("Errors 404!");
                },
                complete: function() {}
            });
        }

        function getEditPassword(id) {
            $.ajax({
                type: "GET",
                dataType: "JSON",
                url: `{{ url('/parents/${id}/password/json') }}`,
                success: function(result) {
                    if (result) {
                        $("#judul").text('Edit Password ');
                        $("#id_reset_password").val(result.id)
                        if (result.real_password) {
                            $("#real_password").html(`Password saat ini : <b>${result.real_password}</b>`)
                        }
                        $("#password").val(result.real_password)
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
        $("#DataParents").addClass("active");
    </script>
@endsection
