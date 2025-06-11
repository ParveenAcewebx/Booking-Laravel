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

	document.addEventListener("DOMContentLoaded", function() {
		@if(session('success'))
		swal({
			title: "Success!",
			text: "{{ session('success') }}",
			icon: "success",
			timer: 2000,
			buttons: false
		});
		@endif

		@if(session('error'))
		swal({
			title: "Error!",
			text: "{{ session('error') }}",
			icon: "error", // changed from 'danger' to 'error'
			timer: 2000,
			buttons: false
		});
		@endif
	});
</script>
@endsection