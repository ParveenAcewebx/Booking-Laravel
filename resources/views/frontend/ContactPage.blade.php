@extends('frontend.layouts.app')
@section('content')

<section class="relative h-64 from-gray-600 flex items-center justify-center text-center text-white">
<div class="bg-black/50 w-full h-full absolute top-0 left-0 z-0"></div>
<h1 class="z-10 text-4xl md:text-5xl font-bold">Contact</h1>
</section>
<section class="bg-gray-50 py-12">
    <div class="container mx-auto px-4">
        <div class="grid md:grid-cols-3 gap-8">
            <div>
                <h2 class="text-4xl font-semibold mb-4">Find Us On Map</h2>
                <div class="w-full h-64 rounded-lg overflow-hidden shadow">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3430.261060838694!2d76.68348421135987!3d30.711060486573334!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390fefcef43347cd%3A0x79b40173ef247e11!2sAceWebX!5e0!3m2!1sen!2sin!4v1758190201022!5m2!1sen!2sin"
                        width="600" height="450"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>

            <div>
                <div class="bg-white p-6 rounded-2xl shadow-lg contact-form">
                    <h2 class="text-3xl font-semibold mb-6">Get in touch with us</h2>

                    @if(session('success'))
                    <div class="mb-1 p-3 bg-green-100 text-green-800 rounded" id ="contact-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('contact.submit') }}" class="space-y-4">
                        @csrf
                        <input type="text" name="name" placeholder="Your Name *" value="{{ old('name') }}"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-pink-500 focus:outline-none @error('name') border-red-500 @enderror" />
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        <input type="email" name="email" placeholder="Your Email *" value="{{ old('email') }}"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-pink-500 focus:outline-none @error('email') border-red-500 @enderror" />
                        @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        <input type="text" name="phone" placeholder="Phone No *" value="{{ old('phone') }}"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-pink-500 focus:outline-none @error('phone') border-red-500 @enderror" />
                        @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        <textarea name="message" placeholder="Your Message *" rows="4"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-pink-500 focus:outline-none @error('message') border-red-500 @enderror">{{ old('message') }}</textarea>
                        @error('message') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        <div class="flex items-center space-x-3">
                            <span>{!! captcha_img('flat') !!}</span>
                            <!-- <button type="button" id="reload" class="p-2 bg-gray-100 rounded-md">🔄</button> -->
                        </div>
                        <input type="text" name="captcha" placeholder="Enter Captcha"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-pink-500 focus:outline-none @error('captcha') border-red-500 @enderror">
                        @error('captcha') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                        <div class="flex space-x-3">
                            <button type="submit"
                                class="px-6 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 shadow">
                                Send Message
                            </button>
                            <button type="reset"
                                class="px-6 py-2 border border-pink-600 text-pink-600 rounded-lg hover:bg-pink-50">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div>
                <h2 class="text-3xl font-semibold mb-4">Contact Details</h2>
                <div class="text-gray-700 space-y-2">
                    <p class="font-semibold text-lg">Royal Multisport Private Limited</p>
                    <p>B Wing, 103-104, Fulcrum,</p>
                    <p>Hiranandani Business Park, Sahar Road,</p>
                    <p>Andheri East, Mumbai Suburban,</p>
                    <p>Maharashtra, 400059.</p>
                </div>
            </div>

        </div>
    </div>
</section>

<script>
    document.getElementById('reload').addEventListener('click', function() {
        fetch('{{ route('captcha.refresh') }}')
            .then(response => response.json())
            .then(data => {
                document.querySelector('span').innerHTML = data.captcha;
            });
    });
</script>

@endsection