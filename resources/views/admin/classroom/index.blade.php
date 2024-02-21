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
              <button type="button" class="btn btn-primary btn-sm" onclick="getCreate()" data-toggle="modal" data-target="#form-classrooms">
                  <i class="nav-icon fas fa-folder-plus"></i> &nbsp; Tambah Data Kelas
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
                    <td>{{ $data->teacher->name }}</td>
                    <td>
                        <form action="{{ route('classrooms.destroy', $data->id) }}" method="post">
                            @csrf
                            @method('delete')
                            <button type="button" class="btn btn-success btn-sm" onclick="getEdit({{$data->id}})" data-toggle="modal" data-target="#form-classrooms">
                              <i class="nav-icon fas fa-edit"></i> &nbsp; Edit
                            </button>
                            <button class="btn btn-danger btn-sm"><i class="nav-icon fas fa-trash-alt"></i> &nbsp; Hapus</button>
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
<div class="modal fade bd-example-modal-md" id="form-classrooms" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
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
                <input type='text' id="name" name='name' class="form-control @error('name') is-invalid @enderror" placeholder="{{ __('Nama Kelas') }}">
              </div>
              <div class="form-group">
                <label for="teacher_id">Wali Kelas</label>
                <select id="teacher_id" name="teacher_id" class="select2bs4 form-control @error('teacher_id') is-invalid @enderror">
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
            <button type="button" class="btn btn-default" data-dismiss="modal"><i class='nav-icon fas fa-arrow-left'></i> &nbsp; Kembali</button>
            <button type="submit" class="btn btn-primary"><i class="nav-icon fas fa-save"></i> &nbsp; Tambahkan</button>
      </form>
      </div>
    </div>
  </div>
</div>

@endsection

@section('script')
  <script>
    function getCreate(){
        $("#judul").text('Tambah Data Kelas');
        $('#id').val('');
        $('#name').val('');
        $('#teacher_id').val('');
    }

    function getEdit(id){
      $.ajax({
        type:"GET",
        dataType:"JSON",
        url:`{{ url('/classrooms/${id}/json') }}`,
        success:function(result){
          if(result){
            $("#judul").text('Edit Data Kelas ' + result.name);
            $('#id').val(result.id);
            $('#name').val(result.name);
            $('#teacher_id').val(result.teacher_id);
          }
        },
        error:function(){
          toastr.error("Errors 404!");
        },
        complete:function(){
        }
      });
    }

    $("#MasterData").addClass("active");
    $("#liMasterData").addClass("menu-open");
    $("#DataClassrooms").addClass("active");
  </script>
@endsection
