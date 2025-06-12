@extends('layouts.app')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
    <div class="page-header">
			<div class="page-block">
				<div class="row align-items-center">
					<div class="col-md-12">
						<div class="page-header-title">
							<h5>Edit Booking Template</h5>
						</div>
						<ul class="breadcrumb">
							<li class="breadcrumb-item"><a href="{{route('dashboard') }}"><i class="feather icon-home"></i></a></li>
							<li class="breadcrumb-item"><a href="#!">Booking Template</a></li>
							<li class="breadcrumb-item"><a href="#!">Edit Booking Template</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
        <div class="card">
            <div class="card-header">
                <h5>Edit Booking Template</h5>
            </div>
            <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="floating-label" for="">Booking Template Name</label>
                                <input type="text" id="bookingTemplatesname" class="form-control"  value="{{ $templates->template_name }}"> </div>
                            </div>
                        </div>
                        <input type="hidden" name="bookingTemplates" id="bookingTemplates" class="form-control"  value="{{ $templates->data }}">
                        <input type="hidden" name="templateid" id="templateid" class="form-control"  value="{{ $templates->id }}">
                        <div id="build-wrap" style=""></div>
                        <div id="render-wrap" style=""></div>
                    </div>
            </div>
    </div>
</div>
@endsection


