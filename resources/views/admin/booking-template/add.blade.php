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
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header d-flex align-items-center justify-content-between">
						<h5>Add Booking Template</h5>
						@if($query->isNotEmpty())
						<div class="page-header-titles align-items-center float-right">
							<button class="btn btn-primary p-2" data-toggle="modal" data-target="#copyTemplateModal">Copy Template</button>
						</div>
						@endif
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
									<label class="floating-label" for="">Booking Template Name <span class="text-danger">*</span></label>
									<div>
										<input type="text" id="bookingTemplatesname" class="form-control" placeholder="Enter name">
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Select Vendor <span class="text-danger"></span></label>
									<select name="vendor"
										class="form-control vendor select-template-vendor">
										<option value="">-- Select Vendor --</option>
										@foreach($activeVendor as $Vendor)
										<option value="{{ $Vendor->id }}"
											{{ old('vendor') == $Vendor->id ? 'selected' : '' }}>
											{{ $Vendor->name }}
										</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Status</label>
									<select name="status"
										class="form-control select-user select-template-status @error('status') is-invalid @enderror">
										<option value="{{ config('constants.status.active') }}"
											{{ old('status', 1) == config('constants.status.active') ? 'selected' : '' }}>
											Active
										</option>
										<option value="{{ config('constants.status.inactive') }}"
											{{ old('status', 1) == config('constants.status.inactive') ? 'selected' : '' }}>
											Inactive
										</option>
									</select>
									@error('status')
									<div class="invalid-feedback d-block">{{ $message }}</div>
									@enderror
								</div>
								<!-- Modal for Copy Template -->
								<div class="modal fade" id="copyTemplateModal" tabindex="-1" role="dialog" aria-labelledby="copyTemplateModalLabel" aria-hidden="true">
									<div class="modal-dialog" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title" id="copyTemplateModalLabel">Copy Template</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<div class="modal-body">
												<select class="form-control" name="Copy_template_id" id="Copy_template_id">
													<option value="" disabled selected>---Select Template---</option>
													@foreach($query as $template)
													<option value="{{ $template->id }}">{{ $template->template_name }}</option>
													@endforeach
												</select>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
												<button type="button" class="btn btn-primary" id="copyTemplateBtn">Copy Template</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<input type="hidden" name="bookingTemplates" id="bookingTemplates" class="form-control" style="margin-top:178px;margin-left: 276px;">
						<input type="hidden" name="bookingTemplates" id="bookingaddpage" class="form-control" style="margin-top:178px;margin-left: 276px;">
						<div id="build-wrap"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
@endsection