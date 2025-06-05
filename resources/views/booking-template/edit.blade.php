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

                        @if(session('success'))
                        <div id="exampleModalCenter" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="exampleModalCenterTitle">Message</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									</div>
									<div class="modal-body">
										<p class="mb-0">{{ session('success') }}</p>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn  btn-secondary" data-dismiss="modal">Okay</button>
										
									</div>
								</div>
							</div>
						</div>
						<button  style="display:none;" id="mymodelsformessage" type="button" class="btn  btn-primary" data-toggle="modal" data-target="#exampleModalCenter">Launch demo modal</button>
                        @endif
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


