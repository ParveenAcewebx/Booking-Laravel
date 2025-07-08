@extends('frontend.layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card shadow rounded-4">
                <div class="card-body p-5">

                    <h2 class="mb-4 text-center">{{ $template->title ?? 'Booking Form' }}</h2>

                    {{-- Success message --}}
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Form --}}
                    <form action="{{ route('form.store', $template->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {!! $formHtml !!}

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-sm">
                                Submit
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
