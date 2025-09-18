@extends('frontend.layouts.app')

@section('content')
    <div class="relative w-full bg-center bg-cover" 
    @if(!empty($pagedata->feature_image))
         style="background-image: url('{{ asset('storage/'.$pagedata->feature_image) }}');"
     @endif >
    <div class="max-w-[85rem] px-4 py-32 sm:px-6 lg:px-8 lg:py-40 mx-auto text-center">
        @if(!empty($pagedata))
            <h1 class="block text-3xl font-bold text-gray-800 sm:text-4xl lg:text-6xl lg:leading-tight dark:text-white">
                {!! html_entity_decode($pagedata->title) !!}
            </h1>
        @endif
    </div>
</div>

<div class="max-w-[85rem] mx-auto px-4 sm:px-6 lg:px-8">
    <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
        @if(!empty($pagedata))
            {!! html_entity_decode($pagedata->content) !!}
        @endif
    </div>
</div>
@endsection
