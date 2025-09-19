@extends('admin.layouts.app')

@section('content')
<section class="pcoded-main-container">
    <div class="pcoded-content">

        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Edit Page</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#!">Pages</a></li>
                            <li class="breadcrumb-item"><a href="#!">Edit Page</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->
        
        <!-- [ Main Content ] start -->
        <form action="{{ route('page.update', $page->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-8 order-md-1">
                    <div class="card">
                        <div class="card-header">
                            <h5>Page Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Title -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Title<span class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control @error('title') is-invalid @enderror"
                                            name="title"
                                            value="{{ old('title', $page->title) }}"
                                            placeholder="Title">
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Slug -->
                                    <div class="form-group">
                                        <label class="form-label">Slug<span class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control @error('slug') is-invalid @enderror"
                                            name="slug"
                                            value="{{ old('slug', $page->slug) }}"
                                            placeholder="Slug">
                                        @error('slug')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <!-- Content -->
                                    <div class="form-group">
                                        <label>Content</label>
                                        <div id="quill-editor" style="height: 200px;">{!! old('description', $page->content) !!}</div>
                                        <textarea name="description"
                                            id="description"
                                            class="d-none @error('description') is-invalid @enderror">{{ old('description', $page->content) }}</textarea>
                                        @error('description')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                            <!-- Meta SEO Fields -->
                            <div class="form-group">
                                <label class="form-label">Meta Title</label>
                                <input type="text" class="form-control @error('meta_title') is-invalid @enderror" name="meta_title" value="{{ old('meta_title', $page->meta_title) }}" placeholder="Meta Title">
                                @error('meta_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Meta Keywords</label>
                                <input type="text" class="form-control @error('meta_keywords') is-invalid @enderror" name="meta_keywords" value="{{ old('meta_keywords',$page->meta_keywords) }}" placeholder="Meta Keywords (comma separated)">
                                @error('meta_keywords')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Meta Description</label>
                                <textarea class="form-control @error('meta_description') is-invalid @enderror" name="meta_description" rows="3" placeholder="Meta Description">{{ old('meta_description', $page->meta_description) }}</textarea>
                                @error('meta_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                           
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Settings & Avatar -->
                <div class="col-md-4 order-md-2">
                    <div class="card">
                        <div class="card-header">
                            <h5>Settings</h5>
                        </div>
                        <div class="card-body">
                            <!-- Status -->
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control @error('status') is-invalid @enderror select-user">
                                    <option value="publish" {{ old('status', $page->status) == 'publish' ? 'selected' : '' }}>Public</option>
                                    <option value="draft" {{ old('status', $page->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="private" {{ old('status', $page->status) == 'private' ? 'selected' : '' }}>Private</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Featured Image Upload -->
                            <div class="form-group">
                                <label class="form-label">Featured Image</label>
                                <div class="input-group mb-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Upload</span>
                                    </div>
                                    <div class="custom-file">
                                        <input type="file"
                                            class="custom-file-input @error('feature_image') is-invalid @enderror"
                                            name="feature_image"
                                            id="addAvatarInput"
                                            accept=".jpg,.jpeg,.png,.gif,image/jpeg,image/png,image/gif">
                                        <label class="custom-file-label overflow-hidden" for="addAvatarInput">Choose file...</label>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Supported image types: JPG, JPEG, PNG, or GIF.</small>
                                @error('feature_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Existing Image Preview -->
                            @if($page->feature_image)
                              <div class="row mt-3"id="add-avatar-preview-container">
                                    <div class="col-md-6 position-relative">
                                        <div class="card shadow-sm">
                                            <img src="{{ asset('storage/' . $page->feature_image) }}"
                                                class="card-img-top img-thumbnail"
                                                alt="Current Featured Image">
                                                <button type="button"
                                                    id="remove-add-avatar-preview"
                                                    class="btn btn-sm btn-dark text-white position-absolute top-0 end-0 m-1 rounded-pill delete-existing-image"
                                                    title="Remove image">
                                                    &times;
                                                </button>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Submit Button -->
                            <div class="text-right mt-3">
                                <button type="submit" class="btn btn-primary">Update Page</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!-- [ Main Content ] end -->
    </div>
</section>
@endsection
