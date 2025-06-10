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
							<h5>All Bookings</h5>
						</div>
						<ul class="breadcrumb">
							<li class="breadcrumb-item"><a href="{{route('dashboard') }}"><i class="feather icon-home"></i></a></li>
							<li class="breadcrumb-item"><a href="{{route('booking.list') }}">Booking</a></li>
							<li class="breadcrumb-item"><a href="{{route('booking.list') }}">All Bookings</a></li>
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
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Okay</button>
									</div>
								</div>
							</div>
						</div>
						<button style="display:none;" id="mymodelsformessage" type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">Launch demo modal</button>
						@endif
					</div>
					<div class="col-md-2">
						<div class="page-header-titles float-right">
							@can('create bookings')
							<a href="{{ route('booking.add')}}" class="btn btn-primary float-right p-2">Add Booking</a>
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
							<table id="booking-list-table" class="table nowrap">
								<thead>
									<tr>
										<th>Created date</th>
										<th>Status</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody>
									<!-- Data will load via Ajax -->
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

<!-- DataTable Script -->
<script>
	$(function() {
		$('#booking-list-table').DataTable({
			destroy: true, // <--- important line!
			processing: true,
			serverSide: true,
			ajax: "{{ route('booking.list') }}",
			columns: [{
					data: 'created_at',
					name: 'created_at',
					orderable: false,
					searchable: false
				},
				{
					data: 'status',
					name: 'status',
					orderable: false,
					searchable: false
				},
				{
					data: 'action',
					name: 'action',
					orderable: false,
					searchable: false
				},
			],
			order: [
				[0, 'desc']
			],
			lengthMenu: [
				[10, 25, 50, 100],
				[10, 25, 50, 100]
			],
		});
	});
</script>
@endsection