@extends('template_backend.home')
@section('heading', 'Profile')
@section('page')
    <li class="breadcrumb-item active">User Profile</li>
@endsection
@section('content')
    <div class="col-12">
        <div class="row">
            <div class="col-5">
                <!-- Profile Image -->
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle" src="{{ asset('img/male.jpg') }}"
                                    alt="User profile picture">
                        </div>
                        <h3 class="profile-username text-center mt-4">Nama: {{ Auth::user()->name }}</h3>
                        <p class="text-muted text-center">Username: {{ Auth::user()->username }}</p>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
@endsection
