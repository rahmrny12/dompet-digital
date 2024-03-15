@extends('template_backend.home')
@section('heading', 'Data Admin')
@section('page')
    <li class="breadcrumb-item active">Data Admin</li>
@endsection
@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <button type="button" class="btn btn-primary btn-sm" onclick="getCreate()" data-toggle="modal"
                        data-target="#form-parents">
                        <i class="nav-icon fas fa-folder-plus"></i> &nbsp; Tambah Data Admin
                    </button>
                </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Admin</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($admins as $key => $data)
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
                    <form action="{{ route('admins.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" id="id" name="id">
                                <div class="form-group" id="form_name">
                                    <label for="name">Nama Admin</label>
                                    <input type='text' id="name" name='name'
                                        class="form-control @error('name') is-invalid @enderror"
                                        placeholder="{{ __('Nama Admin') }}">
                                </div>
                                <div class="form-group" id="form_email">
                                    <label for="email">Email</label>
                                    <input type='text' id="email" name='email'
                                        class="form-control @error('email') is-invalid @enderror"
                                        placeholder="{{ __('Email') }}">
                                </div>
                                <div class="form-group" id="form_password">
                                    <label for="password">Password</label>
                                    <input type='password' id="password" name='password'
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="{{ __('Password') }}">
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
            $("#judul").text('Tambah Data Admin');
            $('#id').val('');
            $('#name').val('');
            $('#email').val('');
            $('#password').val('');
        }

        function getEdit(id) {
            $('#student_id option').removeAttr("selected").trigger('change');

            $.ajax({
                type: "GET",
                dataType: "JSON",
                url: `{{ url('/admins/${id}/json') }}`,
                success: function(result) {
                    if (result) {
                        $("#judul").text('Edit Data Admin ' + result.name);
                        $("#id").val(result.id)
                        $("#name").val(result.name)
                        $("#email").val(result.email)
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
        $("#DataAdmins").addClass("active");
    </script>
@endsection
