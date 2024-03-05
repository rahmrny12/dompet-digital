@extends('template_backend.home')
@section('heading', 'Detail Siswa')
@section('page')
    <li class="breadcrumb-item active"><a href="{{ route('students.index', $setting->service_charge ?? 0) }}">Siswa</a></li>
    <li class="breadcrumb-item active">Detail Siswa</li>
@endsection
@section('content')
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('settings.store') }}" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{ $setting->id ?? null }}">
                    <div class="row no-gutters ml-2 mb-2 mr-2">
                        <div class="col-md-12">
                            <div class="form-group" id="form_service_charge">
                                <label for="service_charge">Service Charge</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp.</span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Service Charge"
                                        id="service_charge" name="service_charge" value="{{ $setting->service_charge ?? null }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-end">
                <button type="submit" class="btn btn-primary"><i class="nav-icon fas fa-save"></i> &nbsp;
                    Simpan</button>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script>
        $("#Settings").addClass("active");
    </script>
@endsection
