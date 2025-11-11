<div class="tab-pane" id="gallery" role="tabpanel">
    <div class="form-group mt-3">
        <label class="form-label">Gallery</label>

        {{-- Add Image Tile --}}
        <div class="col-md-12 mb-3 pr-1 pl-0">
            <label for="galleryInput"
                class="w-100 h-100 d-flex justify-content-center align-items-center border border-primary border-dashed rounded bg-light"
                style="min-height: 150px; cursor: pointer;">
                <div class="text-center text-primary">
                    <div style="font-size: 2rem;">+</div>
                    <div>Add Image</div>
                    <small class="d-block text-muted mt-1">Supported image types: JPG, JPEG, PNG, or GIF.</small>
                </div>
            </label>
            <input type="file" name="gallery[]" id="galleryInput" class="d-none gallery-input" multiple accept=".jpg,.jpeg,.png,.gif,image/jpeg,image/png,image/gif">
        </div>

        {{-- Preview Existing + New --}}
        <div class="row mb-3" id="galleryPreviewContainer">
            @if(isset($service) && $service->gallery)
                @foreach(json_decode($service->gallery) as $image)
                    <div class="col-md-3 mb-3 position-relative existing-image" data-image="{{ $image }}">
                        <div class="card shadow-sm">
                            <img src="{{ asset('storage/' . $image) }}" class="card-img-top img-thumbnail" alt="Gallery Image">
                            <input type="hidden" name="existing_gallery[]" value="{{ $image }}">
                            <button type="button" class="btn btn-sm btn-dark text-white position-absolute top-0 end-0 m-1 rounded-pill delete-existing-image" title="Delete image">&times;</button>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
