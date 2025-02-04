@extends('layouts.app')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="card">
            <div class="card-header">
                <h5>Edit Form</h5>
            </div>
            <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="floating-label" for="">Form Name</label>
                                <input type="text" id="formTemplatesname" class="form-control"  value="{{ $forms->form_name }}">                                     </div>
                            </div>
                        </div>
                        <input type="hidden" name="formTemplates" id="formTemplates" class="form-control"  value="{{ $forms->data }}">
                        <input type="hidden" name="formid" id="formid" class="form-control"  value="{{ $forms->id }}">
                        <div id="build-wrap" style=""></div>
                        <div id="render-wrap" style=""></div>
                    </div>
            </div>
    </div>
</div>
@endsection


