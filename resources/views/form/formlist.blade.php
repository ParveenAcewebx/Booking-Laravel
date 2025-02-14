@extends('layouts.app')

@section('content')

<div class="pcoded-main-container">
	<div class="pcoded-content">
		<!-- [ breadcrumb ] start -->
		<div class="page-header">
			<div class="page-block">
				<div class="row align-items-center">
					<div class="col-md-12">
						<div class="page-header-title">
							<h5>All Forms</h5>
						</div>
						<ul class="breadcrumb">
							<li class="breadcrumb-item"><a href="{{route('home') }}"><i class="feather icon-home"></i></a></li>
							<li class="breadcrumb-item"><a href="#!">Form</a></li>
							<li class="breadcrumb-item"><a href="#!">All Forms</a></li>
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
		<!-- [ breadcrumb ] end -->
		<!-- [ Main Content ] start -->
		<div class="row">
			<div class="col-lg-12">
				<div class="card user-profile-list">
					<div class="card-body">
						<div class="dt-responsive table-responsive">
							<table id="form-list-table" class="table nowrap">
								<thead>
									<tr>
										<th>Name</th>
										<th>Start date</th>
										<th>Status</th>
									</tr>
								</thead>
								<tbody>
                                @foreach($allform as $form)
									<tr>
										<td>
											<div class="d-inline-block align-middle">
												<div class="d-inline-block">
													<h6 class="m-b-0">{{ $form->form_name }}</h6>
												</div>
											</div>
										</td>
										<td>{{ $form->created_at }}</td>
										<td>
											<span class="badge badge-light-success">Active</span>
											<div class="overlay-edit">
                      						<a href="{{route('form.edit', [$form->id])}}" class="btn btn-icon btn-success" data-toggle="tooltip" data-placement="top" title="Edit Form" ><i class="fas fa-pencil-alt"></i></a>
											@can('manage')
											<form action="{{ route('form.delete', [$form->id]) }}" method="POST" id="deleteForm-{{$form->id}}">
												<input type="hidden" name="_method" value="DELETE">
												@csrf
												<button onclick="return deleteForm({{$form->id}})" class="btn btn-icon btn-danger" data-toggle="tooltip" data-placement="top" title="Delete Form"><i class="feather icon-trash-2"></i></button>
                                           </form>
										   @endcan
											</div>
										</td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- [ Main Content ] end -->
	</div>
	
</div>

  
 @endsection