@extends('template_backend.home')
@section('heading', 'Trash Wali Murid')
@section('page')
    <li class="breadcrumb-item active">Trash Wali Murid</li>
@endsection
@section('content')
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Trash Data Wali Murid</h3>
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
                        @foreach ($parents as $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $data->name }}</td>
                                <td>
                                    <form action="{{ route('parents.kill', Crypt::encrypt($data->id)) }}" method="post">
                                        @csrf
                                        @method('delete')
                                        <a href="{{ route('parents.restore', Crypt::encrypt($data->id)) }}"
                                            class="btn btn-success btn-sm mt-2"><i class="nav-icon fas fa-undo"></i> &nbsp;
                                            Restore</a>
                                        <button class="btn btn-danger btn-sm mt-2"><i class="nav-icon fas fa-trash-alt"></i>
                                            &nbsp; Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $("#ViewTrash").addClass("active");
        $("#liViewTrash").addClass("menu-open");
        $("#DataTrashParents").addClass("active");
    </script>
@endsection
