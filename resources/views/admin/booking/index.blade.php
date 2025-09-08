@extends('admin.layouts.app')

@section('content')
<div class="pcoded-main-container">
	<div class="pcoded-content">
		<div class="page-header">
			<div class="page-block">
				<div class="row align-items-center">
					<div class="col-md-10">
						<div class="page-header-title">
							<h5>All Bookings</h5>
						</div>
						<ul class="breadcrumb">
							<li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
							<li class="breadcrumb-item"><a href="{{ route('booking.list') }}">Bookings</a></li>
							<li class="breadcrumb-item"><a href="{{ route('booking.list') }}">All Bookings</a></li>
						</ul>
					</div>
					<div class="col-md-2">
						@can('create bookings')
						<a href="{{ route('booking.add') }}" class="btn btn-primary float-right p-2">Add Booking</a>
						@endcan
					</div>
				</div>
			</div>
		</div>

		{{-- 🔎 Filters --}}


		<div class="row">
			<div class="col-lg-12">

				<div class="card user-profile-list">
					<div class="card-body">
						<div class="row mb-3 justify-content-end">
							<div class="col-md-2 col-sm-6">
								<select id="filter-template" class="form-control select-template-name">
									<option value="">-- Select Template --</option>
									@foreach($templates as $template)
									<option value="{{ $template->id }}">{{ $template->template_name }}</option>
									@endforeach
								</select>
							</div>

							<div class="col-md-2 col-sm-6">
								<select id="filter-customer" class="form-control select-users">
									<option value="">-- Select Booked By --</option>
									@foreach($customers as $customer)
									<option value="{{ $customer->id }}">{{ $customer->name }}</option>
									@endforeach
								</select>
							</div>

							<div class="col-md-1 col-sm-4">
								<button id="reset-filters" class="btn btn-primary float-right p-2 w-100">Reset</button>
							</div>
						</div>
						<div class="dt-responsive table-responsive">
							<table id="booking-list-table" class="table table-striped nowrap" width="100%">
								<thead>
									<tr>
										<th style="display:none;">ID</th>
										<th>Template Name</th>
										<th>Booked By</th>
										<th>Created Date</th>
										<th>Status</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$(function() {
		let table = $('#booking-list-table').DataTable({
			processing: true,
			serverSide: true,
			ajax: {
				url: "{{ route('booking.list') }}",
				data: function(d) {
					d.template_id = $('#filter-template').val();
					d.customer_id = $('#filter-customer').val();
				}
			},
			columns: [{
					data: 'id',
					name: 'id',
					visible: false
				},
				{
					data: 'template_name',
					name: 'template_name'
				},
				{
					data: 'booked_by',
					name: 'booked_by'
				},
				{
					data: 'created_at',
					name: 'created_at'
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
				}
			],
			order: [
				[3, 'desc']
			],
			lengthMenu: [
				[10, 25, 50, 100],
				[10, 25, 50, 100]
			],
		});

		// 🔎 Reload table on filter change
		$('#filter-template, #filter-customer').change(function() {
			table.ajax.reload();
		});

		// 🔄 Reset both filters
		$('#reset-filters').click(function() {
			$('#filter-template').val('').trigger('change'); // trigger change for select2/select-user
			$('#filter-customer').val('').trigger('change');
			table.ajax.reload();
		});
	});
</script>
@endsection