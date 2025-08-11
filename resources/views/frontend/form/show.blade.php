@extends('frontend.layouts.app')

@section('content')
<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-xl mx-auto bg-white shadow rounded-xl border border-gray-200 p-8">
            <h2 class="mb-6 text-center text-2xl font-bold text-gray-800">{{ $template->template_name }}</h2>

            {{-- Success message --}}
            @if (session('success'))
                <div class="mb-4 rounded bg-green-100 px-4 py-3 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Form --}}
            <form action="{{ route('form.store', $template->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                {!! $formHtml !!}
            </form>
        </div>
    </div>
</div>
@endsection
