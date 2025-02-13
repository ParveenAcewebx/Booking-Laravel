@extends('layouts.app')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
                    <div class="card">
                        <div class="card-header">
                        <h5>Add Form </h5>
                        </div>
                        <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="floating-label" for="">Form Name</label>
                                            <input type="text" id="formTemplatesname" class="form-control" >                                     
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="formTemplates" id="formTemplates" class="form-control" style="margin-top:178px;margin-left: 276px;">
                                <input type="hidden" name="formTemplates" id="formsaddpage" class="form-control" style="margin-top:178px;margin-left: 276px;">
                                <div id="build-wrap" ></div>
                        </div>
                    </div>
    </div>
</div>

@endsection