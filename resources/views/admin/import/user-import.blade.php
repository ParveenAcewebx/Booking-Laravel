@extends('admin.layouts.app')
@section('content')
<section class="pcoded-main-container">
    <div class="pcoded-content">

        <!-- Breadcrumb -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Manage User Import</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#!">Import</a></li>
                            <li class="breadcrumb-item"><a href="#!">Manage User Import</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <form action="{{ route('user.import.save') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">

                <div class="col-md-8 order-md-1">
                    <div class="card">
                        <div class="card-header"><h5>Import Settings</h5></div>
                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-12 mb-3">
                                    <a href="{{ route('user.import.sample') }}" class="btn btn-success">
                                        <i class="feather icon-download"></i> Download Sample File
                                    </a>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Excel File <span class="text-danger">*</span></label>
                                        <div class="input-group mb-1">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Upload</span>
                                            </div>
                                            <div class="custom-file">
                                                <input type="file"
                                                    class="custom-file-input @error('excel_file') is-invalid @enderror"
                                                    name="excel_file"
                                                    id="excelFileInput"
                                                    accept=".xlsx,.xls">
                                                <label class="custom-file-label overflow-hidden" for="excelFileInput">Choose file...</label>
                                            </div>
                                        </div>
                                        <small class="form-text text-muted">Supported file types: XLSX, XLS</small>
                                    </div>
                                </div>

                                <!-- Send Email -->
                                <div class="col-md-12 mt-2">
                                    <div class="form-check">
                                        <input type="checkbox" name="send_email" class="form-check-input" id="sendEmailCheckbox" value="1" {{ old('send_email') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="sendEmailCheckbox">Send Email</label>
                                        <i class="feather icon-info ml-1" title="Notify users via email after import"></i>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary">Import</button>
                            <a href="{{ route('user.list') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-4 order-md-2">
                    <div class="card">
                        <div class="card-header"><h5>Instructions</h5></div>
                        <div class="card-body">
                            <p class="text-muted mb-2"><i class="feather icon-info text-primary"></i> Use the sample file format for bulk user import.</p>
                            <p class="text-muted mb-2"><i class="feather icon-check text-success"></i> Required columns: Name, Email, Password, Phone Number, Status.</p>
                            <p class="text-muted"><i class="feather icon-mail text-warning"></i> If "Send Email" is checked, users will receive login credentials after import.</p>
                        </div>
                    </div>
                </div>

            </div>
        </form>

    </div>
</section>
@endsection
