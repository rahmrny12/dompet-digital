@extends('template_backend.home')
@section('heading', 'Data Kelas')
@section('page')
  <li class="breadcrumb-item active">Data Kelas</li>
@endsection
@section('content')
<div class="col-md-12">
    <div class="card">
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
                        <a href="{{ route('transactions', $data->id) }}" class="btn btn-info text-light btn-sm px-4">Lihat</a>
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

@endsection

@section('script')
  <script>
    $("#TransactionHistory").addClass("active");
  </script>
@endsection
