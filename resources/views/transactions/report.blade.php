@extends('template_backend.home')
@section('heading', 'Laporan Data Isi Ulang')
@section('page')
    <li class="breadcrumb-item active">Data Riwayat Belanja</li>
@endsection
@section('content')
    <div class="col-md-12">
        <div class="card">
            <!-- /.card-header -->
            <div class="card-body">
                <form action="" method="get">
                    <div class="d-flex justify-content-end align-items-center">
                        <div class="mb-3 ml-3 px-0">
                            <input type='date' value="{{ request('from_date') ?: now()->format('Y-m-d') }}"
                                id="from_date" name='from_date' class="form-control">
                        </div>
                        <div class="mb-3 ml-3 px-0">
                            <input type='date' value="{{ request('to_date') ?: now()->format('Y-m-d') }}"
                                id="to_date" name='to_date' class="form-control">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary ml-3 px-3">Filter</button>
                        </div>
                    </div>
                </form>
                <table id="example1" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>NISN</th>
                            <th>Nama Siswa</th>
                            <th>Nominal</th>
                            <th>Biaya Layanan</th>
                            <th>Nominal Bersih</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $totalNominal = 0;
                        $totalServiceCharge = 0; ?>
                        @foreach ($recharge as $key => $data)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $data->student->nisn }}</td>
                                <td>{{ $data->student->name }}</td>
                                <td>Rp. {{ number_format($data->amount, 0, ',', '.') }}</td>
                                <td>Rp. {{ number_format($data->service_charge, 0, ',', '.') }}</td>
                                <td>Rp. {{ number_format($data->amount - $data->service_charge, 0, ',', '.') }}</td>
                                <?php
                                $totalNominal += $data->amount;
                                $totalServiceCharge += $data->service_charge;
                                ?>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="3" align="end">Total : </td>
                            <td>Rp. {{ number_format($totalNominal, 0, ',', '.') }}</td>
                            <td>Rp. {{ number_format($totalServiceCharge, 0, ',', '.') }}</td>
                            <td>Rp. {{ number_format($totalNominal - $totalServiceCharge, 0, ',', '.') }}</td>
                        </tr>
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
        $("#Report").addClass("active");
    </script>
@endsection
