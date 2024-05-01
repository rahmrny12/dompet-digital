@extends('template_backend.home')
@section('heading', "Riwayat Belanja Kelas $classroom->name")
@section('page')
  <li class="breadcrumb-item active">Data Riwayat Belanja</li>
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
                    <th>Waktu Transaksi</th>
                    <th>NISN</th>
                    <th>Nama Siswa</th>
                    <th>Nominal</th>
                    <th>Note</th>
                    <th>Admin</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $key => $data)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $data->created_at }}</td>
                    <td>{{ $data->nisn }}</td>
                    <td>{{ $data->name }}</td>
                    <td>Rp. {{ number_format($data->total_payment, 0, ',', '.') }}</td>
                    <td>{{ $data->note ?? '-' }}</td>
                    <td>{{ $data->admin ?? '-' }}</td>
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
