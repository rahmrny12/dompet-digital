@php
    use Carbon\Carbon;
@endphp
@extends('template_backend.home')
@section('heading', 'Dashboard')
@section('page')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection
@section('content')
    <div class="col-md-12" id="load_content">
        <div class="row">
            <div class="col-lg-4 col-4">
              <!-- small box -->
              <div class="small-box bg-info">
                <div class="inner">
                  <h3>Rp. {{ number_format($dashboard->transaction_total, 0, ',', '.') }}</h3>

                  <p>Transaksi Hari Ini</p>
                </div>
                <div class="icon">
                  <i class="fas fa-wallet"></i>
                </div>
                <a href="{{ route('transactions.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-4 col-4">
              <!-- small box -->
              <div class="small-box bg-info">
                <div class="inner">
                  <h3>Rp. {{ number_format($dashboard->service_charge_total, 0, ',', '.') }}</h3>

                  <p>Biaya Layanan Hari Ini</p>
                </div>
                <div class="icon">
                  <i class="fas fa-wallet"></i>
                </div>
                <a href="{{ route('transactions.balance-report') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-4 col-4">
              <!-- small box -->
              <div class="small-box bg-success">
                <div class="inner">
                  <h3>{{ $dashboard->student_count }}</h3>

                  <p>Siswa</p>
                </div>
                <div class="icon">
                  <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('classrooms.index') }}"  class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-4 col-4">
              <!-- small box -->
              <div class="small-box bg-warning">
                <div class="inner">
                  <h3>{{ $dashboard->teacher_count }}</h3>

                  <p>Guru</p>
                </div>
                <div class="icon">
                  <i class="fas fa-user"></i>
                </div>
                <a href="{{ route('teachers.index') }}"  class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-4 col-4">
              <!-- small box -->
              <div class="small-box bg-danger">
                <div class="inner">
                  <h3>{{ $dashboard->classroom_count }}</h3>

                  <p>Kelas</p>
                </div>
                <div class="icon">
                  <i class="fas fa-school"></i>
                </div>
                <a href="{{ route('classrooms.index') }}"  class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
          </div>
    </div>

    <div class="col-md-6">
        <div class="card card-warning" style="min-height: 385px;">
            <div class="card-header" style="color: white;">
                <h3 class="card-title col-md-11">
                    Pengumuman
                </h3>
                <a href="{{ route('announcement.edit') }}"><i class="fas fa-edit"></i></a>
            </div>
            <div class="card-body">
                <div class="tab-content p-0">
                    {!! $announcement->content ?? 'Pengumuman Kosong' !!}
                </div>
            </div>
        </div>
    </div>

    <script>
        $("#home").addClass("active");
    </script>
@endsection
