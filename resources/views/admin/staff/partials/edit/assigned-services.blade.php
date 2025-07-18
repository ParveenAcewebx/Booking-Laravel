<div class="tab-pane fade show active" id="assigned-services" role="tabpanel">
    <button type="button" id="addServicesBtn" class="btn btn-sm btn-primary mb-3" data-toggle="modal" data-target="#servicesModal">
        <i class="feather icon-plus"></i> Add Services
    </button>

    <div class="table-responsive">
        <table class="table table-hover mb-0" id="servicesTable">
            <thead class="bg-light">
                <tr>
                    <th class="text-left">Service</th>
                    <th class="text-center">Price</th>
                    <th class="text-center">Remove</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($assignedServices as $service)
                    <tr data-id="{{ $service->id }}">
                        <td>
                            <input type="hidden" name="assigned_services[{{ $service->id }}][id]" value="{{ $service->id }}">
                            {{ $service->name }}
                        </td>
                        <td class="text-center">
                            <input type="text" name="assigned_services[{{ $service->id }}][price]"
                                class="form-control form-control-sm text-center"
                                value="${{ number_format($service->price, 2) }}" readonly>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-danger remove-service">REMOVE</button>
                        </td>
                    </tr>
                @empty
                    <tr id="noServiceRow">
                        <td colspan="3" class="text-center text-muted">No services assigned yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @include('admin.staff.partials.modals.services')
</div>
