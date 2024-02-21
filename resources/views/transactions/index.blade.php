@extends('template_backend.home')
@section('heading', "Riwayat Belanja Kelas $classroom->name")
@section('page')
  <li class="breadcrumb-item active">Data Riwayat Belanja</li>
@endsection
@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
          <h3 class="card-title">
              <a href="{{ route('transaction.entry-balance') }}" class="btn btn-primary btn-sm">
                  <i class="nav-icon fas fa-folder-plus"></i> &nbsp; Input Saldo Siswa
              </a>
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
                    <th>Nominal</th>
                    <th>Note</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $key => $data)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $data->nisn }}</td>
                    <td>{{ $data->name }}</td>
                    <td>Rp. {{ number_format($data->total_payment, 0, ',', '.') }}</td>
                    <td>{{ $data->note }}</td>
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
