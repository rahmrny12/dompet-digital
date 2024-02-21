@extends('template_backend.home')
@section('heading', 'Data Siswa')
@section('page')
  <li class="breadcrumb-item active">Data Siswa</li>
@endsection
@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
          <h3 class="card-title">
              <button type="button" class="btn btn-primary btn-sm" onclick="getCreate()" data-toggle="modal" data-target="#form-students">
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
                            <button type="button" class="btn btn-success btn-sm" onclick="getEdit({{$data->id}})" data-toggle="modal" data-target="#form-students">
                              <i class="nav-icon fas fa-edit"></i> &nbsp; Edit
                            </button>
                            <a class="btn btn-info btn-sm text-white" href="{{ route('students.show', Crypt::encrypt($data->id)) }}">
                                <i class="nav-icon fas fa-money-check"></i> &nbsp; Detail
                              </a>
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
<div class="modal fade bd-example-modal-md" id="form-students" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
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
              <div class="form-group" id="form_nisn">
                <label for="nisn">NISN</label>
                <input type='text' id="nisn" name='nisn' class="form-control @error('nisn') is-invalid @enderror" placeholder="{{ __('NISN') }}">
              </div>
              <div class="form-group" id="form_name">
                <label for="name">Nama Siswa</label>
                <input type='text' id="name" name='name' class="form-control @error('name') is-invalid @enderror" placeholder="{{ __('Nama Siswa') }}">
              </div>
              <div class="form-group">
                <label for="classroom_id">Kelas</label>
                <select id="classroom_id" name="classroom_id" class="form-control @error('classroom_id') is-invalid @enderror">
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
        $("#judul").text('Tambah Data Siswa');
        $('#id').val('');
        $('#nisn').val('');
        $('#name').val('');
        $('#classroom_id').val('');
    }

    function getEdit(id){
      $.ajax({
        type:"GET",
        dataType:"JSON",
        url:`{{ url('/students/${id}/json') }}`,
        success:function(result){
          if(result){
            $("#judul").text('Edit Data Siswa ' + result.name);
            $('#id').val(result.id);
            $('#nisn').val(result.nisn);
            $('#name').val(result.name);
            $('#classroom_id').val(result.classroom_id);
            // $(`#classroom_id option[value=${result.classroom_id}]`).attr('selected','selected');
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
    $("#DataStudents").addClass("active");
  </script>
@endsection
