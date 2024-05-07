@extends('template_backend.home')
@section('heading', 'Input Saldo Siswa')
@section('page')
    <li class="breadcrumb-item active">Input Saldo Siswa</li>
@endsection
@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <a href="{{ route('transactions.index') }}" class="btn btn-primary btn-sm">
                        <i class="nav-icon fas fa-money-check"></i> &nbsp; Histori Belanja Siswa
                    </a>
                </h3>
            </div>
            <!-- /.card-header -->
            <form action="{{ route('transactions.entry-balance') }}" method="post">
                @csrf
                <div class="card-body row">
                    <div class="col-md-12 col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="kelas">Kelas</label>
                                    <select id="classroom_id" name="classroom_id"
                                        class="select2bs4 form-control @error('classroom_id') is-invalid @enderror">
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach ($classrooms as $data)
                                            <option value="{{ $data->id }}"
                                                {{ old('classroom_id') == $data->id ? 'selected' : '' }}>
                                                {{ $data->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group" id="form_student_id">
                                    <label for="student_id">Siswa</label>
                                    <select id="student_id" name="student_id"
                                        class="select2bs4 form-control @error('student_id') is-invalid @enderror">
                                        <option value="">-- Pilih Siswa --</option>
                                    </select>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <div class="col-md-12 col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-right mb-3">
                                    <span class="text-sm">Dipotong biaya layanan sebesar : <span
                                            class="font-weight-bold text-warning">Rp.
                                            {{ number_format($service_charge, 0, ',', '.') }}</span></span>
                                </div>
                                <h5 class="text-lg">Saldo Saat Ini : <span id="current_balance">-</span></h5>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp.</span>
                                    </div>
                                    <input type="text" class="form-control " placeholder="Isi ulang saldo" id="balance"
                                        name="balance">
                                    <button class="btn btn-outline-success" type="submit" id="button-addon2"
                                        style="border-top-left-radius: 0; border-bottom-left-radius: 0">Tambah</button>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
    </form>


    <!-- Extra large modal -->
    <div class="modal fade bd-example-modal-md" id="form-students" tabindex="-1" role="dialog"
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
                    <form action="{{ route('students.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" id="id" name="id">
                                <div class="form-group" id="form_nisn">
                                    <label for="nisn">NISN</label>
                                    <input type='text' id="nisn" name='nisn'
                                        class="form-control @error('nisn') is-invalid @enderror"
                                        placeholder="{{ __('NISN') }}">
                                </div>
                                <div class="form-group" id="form_name">
                                    <label for="name">Nama Siswa</label>
                                    <input type='text' id="name" name='name'
                                        class="form-control @error('name') is-invalid @enderror"
                                        placeholder="{{ __('NaF Siswa') }}">
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i
                            class='nav-icon fas fa-arrow-left'></i> &nbsp; Kembali</button>
                    <button type="submit" class="btn btn-primary"><i class="nav-icon fas fa-save"></i> &nbsp;
                        Tambahkan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        const old_classroom_id = "{{ old('classroom_id') }}";
        if (old_classroom_id) {
            $('#form_student_id').show()
            getStudents()
        } else {
            $('#form_student_id').hide()
        }

        $("#classroom_id").on('change', getStudents)
        $("#student_id").on('change', getBalance)

        let studentsResult;

        function getStudents() {
            const id = $("#classroom_id").val()
            if (id) {
                $.ajax({
                    type: "GET",
                    dataType: "JSON",
                    url: `{{ url('/classrooms/${id}/students/json') }}`,
                    success: function(result) {
                        studentsResult = result;

                        const formStudentId = $('#form_student_id');
                        const inputStudentId = $('#student_id');

                        formStudentId.next('span.text-danger').remove();

                        if (result && result.length > 0) {
                            inputStudentId.empty().append('<option value="">-- Pilih Siswa --</option>');
                            result.forEach(item => inputStudentId.append(
                                `<option value="${item.id}">${item.name}</option>`));
                            formStudentId.show();
                        } else {
                            formStudentId.hide().after(
                                '<span class="text-danger font-weight-bold">Siswa kosong.</span>');
                        }
                    },

                    error: function() {
                        toastr.error("Errors 404!");
                    },
                    complete: function() {}
                });
            }
        }

        function getBalance() {
            const studentId = $('#student_id').val();
            const currentBalance = studentsResult.find((item) => item['id'] == studentId)?.balance?.current_balance ?? 0;

            var formattedBalance = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(currentBalance);

            $('#current_balance').html(formattedBalance)
            if (currentBalance == 0) {
                $('#current_balance').addClass('text-danger').removeClass('text-success')
            } else {
                $('#current_balance').addClass('text-success').removeClass('text-danger')
            }
        }

        $("#EntryBalance").addClass("active");
    </script>
@endsection
