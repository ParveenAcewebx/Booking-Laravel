	@extends('layouts.app')

	@section('content')

	<div class="pcoded-main-container">
		<div class="pcoded-content">
			<!-- [ breadcrumb ] start -->
			<div class="page-header">
				<div class="page-block">
					<div class="row align-items-center">
						<div class="col-md-10">
							<div class="page-header-title">
								<h5>All Users</h5>
							</div>
							<ul class="breadcrumb">
								<li class="breadcrumb-item"><a href="{{route('dashboard') }}"><i class="feather icon-home"></i></a></li>
								<li class="breadcrumb-item"><a href="#!">User</a></li>
								<li class="breadcrumb-item"><a href="#!">All Users</a></li>
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
							<button style="display:none;" id="mymodelsformessage" type="button" class="btn  btn-primary" data-toggle="modal" data-target="#exampleModalCenter">Launch demo modal</button>
							@endif
						</div>
						<div class="col-md-2">
							<div class="page-header-titles float-right">
								@can('create users')
								<a href="{{ route('user.add')}}" class="btn btn-primary float-right p-2">Add User</a>
								@endcan
							</div>
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
								<table id="user-list-table" class="table nowrap">
									<thead>
										<tr>
											<th>Name</th>
											<th>Created date</th>
											<th>Status</th>
											<th>Actions</th>
										</tr>
									</thead>
									<tbody>
										@foreach($allusers as $user)
										<tr>
											<td>
												<div class="d-inline-block align-middle">
													<div class="d-inline-block">
														<h6 class="m-b-0">{{ $user->name }}</h6>
														<p class="m-b-0">{{ $user->email }}</p>
													</div>
												</div>
											</td>
											<td>{{ $user->created_at }}</td>
											<td>
												<span class="badge badge-light-success">Active</span>

											</td>
											<td>
												<div class="overlay-edit">
													@if(Auth::id() == $user->id)
													<a href="{{ route('user.edit', [$user->id]) }}" class="btn btn-icon btn-success" data-toggle="tooltip" data-placement="top" title="Edit User">
														<i class="fas fa-pencil-alt"></i>
													</a>
													@else
													@can('edit users')

													<a href="{{ route('user.edit', [$user->id]) }}" class="btn btn-icon btn-success" data-toggle="tooltip" data-placement="top" title="Edit User">
														<i class="fas fa-pencil-alt"></i>
													</a>
													@endcan
													@endif
													@can('delete users')
													@if(Auth::id() != $user->id)
													<form action="{{route('user.delete', [$user->id])}}" method="POST" id="deleteUser-{{$user->id}}">
														<input type="hidden" name="_method" value="DELETE">
														@csrf
														<button onclick="return deleteUser({{$user->id}})" class="btn btn-icon btn-danger" data-toggle="tooltip" data-placement="top" title="Delete User"><i class="feather icon-trash-2"></i></button>
													</form>
													@endif
													@endcan
													@php
													$isImpersonating = session()->has('impersonate_original_user') || Cookie::get('impersonate_original_user');
													$currentUser = Auth::user();
													@endphp

													@if($isImpersonating && Auth::id() === $user->id)
													<!-- Show Switch Back only for the impersonated user -->
													<form method="POST" action="{{ route('user.switch.back') }}">
														@csrf
														<button type="submit" class="btn btn-sm btn-dark" data-toggle="tooltip" data-placement="top" title="Switch Back to {{ $loginUser->name }}">
															<i class="feather icon-log-out"></i>
														</button>
													</form>

													@elseif(!$isImpersonating && $currentUser->hasRole('Administrator') && $currentUser->id !== $user->id)
													<form method="POST" action="{{ route('user.switch', $user->id) }}">
														@csrf
														<button type="submit" class="btn btn-sm btn-dark" data-toggle="tooltip" data-placement="top" title="Switch User">
															<i class="fas fa-random"></i>
														</button>
													</form>

													@elseif($currentUser->id === $user->id)
													<span class="badge bg-info" data-toggle="tooltip" data-placement="top" title="You are logged in as this user">
														<i class="feather icon-user-check"></i>
													</span>
													@endif
												</div>
											</td>
										</tr>
										@endforeach

									</tbody>
									<!-- <tfoot>
										<tr>
											<th>Name</th>
											<th>Start date</th>
											<th>Status</th>
										</tr>
									</tfoot> -->
								</table>
								<script>
									// DataTable start
									$('#user-list-table').DataTable();
									// DataTable end
								</script>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- [ Main Content ] end -->
		</div>

	</div>
	@endsection