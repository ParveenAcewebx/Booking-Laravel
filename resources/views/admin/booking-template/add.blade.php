@extends('admin.layouts.app')
@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
    	<div class="page-header">
			<div class="page-block">
				<div class="row align-items-center">
					<div class="col-md-12">
						<div class="page-header-title">
							<h5>Add Booking Template</h5>
						</div>
						<ul class="breadcrumb">
							<li class="breadcrumb-item"><a href="{{route('dashboard') }}"><i class="feather icon-home"></i></a></li>
							<li class="breadcrumb-item"><a href="#!">Booking Template</a></li>
							<li class="breadcrumb-item"><a href="#!">Add Booking Template</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
        <div class="card">
            <div class="card-header">
                <h5>Add Booking Template</h5>
            </div>
                <div class="card-body">
					<div class="row">
						<div class="col-sm-4">
							<div class="form-group">
								<label class="floating-label" for="">Booking Template Name <span class="text-danger">*</span></label>
								<div>
									<input type="text" id="bookingTemplatesname" class="form-control" placeholder="Enter name">
								</div>
							</div>
						</div>
					</div>
					<input type="hidden" name="bookingTemplates" id="bookingTemplates" class="form-control" style="margin-top:178px;margin-left: 276px;">
					<input type="hidden" name="bookingTemplates" id="bookingaddpage" class="form-control" style="margin-top:178px;margin-left: 276px;">
                    <div id="build-wrap" ></div>
                </div>
            </div>
    	</div>
	</div>
</div>
@endsection