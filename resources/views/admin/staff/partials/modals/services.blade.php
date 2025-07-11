<div class="modal fade" id="servicesModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Select Services</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row ml-1" id="servicesList">
                    @foreach($services as $service)
                    <div class="custom-control custom-checkbox service-option mr-2" data-id="{{ $service->id }}">
                        <input type="checkbox" class="custom-control-input service-checkbox" id="service_{{ $service->id }}" value="{{ $service->id }}" data-name="{{ $service->name }}" data-price="{{ $service->price }}">
                        <label class="custom-control-label" for="service_{{ $service->id }}">
                            {{ $service->name }} - ${{ number_format($service->price, 2) }}
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="addSelectedServices" class="btn btn-success">Add Selected</button>
            </div>
        </div>
    </div>
</div>