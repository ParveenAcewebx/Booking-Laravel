<!-- ========== FOOTER ========== -->
<footer class="mt-auto bg-gray-900 w-full dark:bg-neutral-950">
    <div class="mt-auto w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 lg:pt-20 mx-auto">
        <!-- Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
            <div class="col-span-full lg:col-span-1">

                <a href="/" class="flex items-center space-x-3 text-xl font-semibold text-black dark:text-white">
                    @php
                        $logo = get_setting('website_logo');
                        $logoStoragePath = 'public/' . $logo; // Adjust if necessary
                    @endphp
                    @if ($logo && Storage::exists($logoStoragePath))
                        <img src="{{ asset('storage/' . $logo) }}" alt="MyBrand Logo" class="w-auto">
                    @else
                        <img src="{{ asset('assets/images/no-image-available.png') }}" alt="No Image" class="w-auto">
                    @endif
                </a>
            </div>
            <!-- End Col -->

            <div class="col-span-1">
                <h4 class="font-semibold text-gray-100">Product</h4>

                <div class="mt-3 grid space-y-3">
                    <p><a class="inline-flex gap-x-2 text-gray-400 hover:text-gray-200 focus:outline-hidden focus:text-gray-200 dark:text-neutral-400 dark:hover:text-neutral-200 dark:focus:text-neutral-200" href="#">Pricing</a></p>
                    <p><a class="inline-flex gap-x-2 text-gray-400 hover:text-gray-200 focus:outline-hidden focus:text-gray-200 dark:text-neutral-400 dark:hover:text-neutral-200 dark:focus:text-neutral-200" href="#">Changelog</a></p>
                    <p><a class="inline-flex gap-x-2 text-gray-400 hover:text-gray-200 focus:outline-hidden focus:text-gray-200 dark:text-neutral-400 dark:hover:text-neutral-200 dark:focus:text-neutral-200" href="#">Docs</a></p>
                </div>
            </div>
            <!-- End Col -->

            <div class="col-span-1">
                <h4 class="font-semibold text-gray-100">Company</h4>

                <div class="mt-3 grid space-y-3">
                    <p><a class="inline-flex gap-x-2 text-gray-400 hover:text-gray-200 focus:outline-hidden focus:text-gray-200 dark:text-neutral-400 dark:hover:text-neutral-200 dark:focus:text-neutral-200" href="#">About us</a></p>
                    <p><a class="inline-flex gap-x-2 text-gray-400 hover:text-gray-200 focus:outline-hidden focus:text-gray-200 dark:text-neutral-400 dark:hover:text-neutral-200 dark:focus:text-neutral-200" href="#">Blog</a></p>
                    <p><a class="inline-flex gap-x-2 text-gray-400 hover:text-gray-200 focus:outline-hidden focus:text-gray-200 dark:text-neutral-400 dark:hover:text-neutral-200 dark:focus:text-neutral-200" href="#">Careers</a> <span class="inline-block ms-1 text-xs bg-blue-700 text-white py-1 px-2 rounded-lg">We're hiring</span></p>
                    <p><a class="inline-flex gap-x-2 text-gray-400 hover:text-gray-200 focus:outline-hidden focus:text-gray-200 dark:text-neutral-400 dark:hover:text-neutral-200 dark:focus:text-neutral-200" href="#">Customers</a></p>
                </div>
            </div>
            <!-- End Col -->

            <div class="col-span-2">
                <h4 class="font-semibold text-gray-100">Stay up to date</h4>

                <form>
                    <div class="mt-4 flex flex-col items-center gap-2 sm:flex-row sm:gap-3 bg-white rounded-lg p-2 dark:bg-neutral-900">
                        <div class="w-full">
                            <label for="hero-input" class="sr-only">Subscribe</label>
                            <input type="text" id="hero-input" name="hero-input" class="py-2.5 sm:py-3 px-4 block w-full border-transparent rounded-lg sm:text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-transparent dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="Enter your email">
                        </div>
                        <a class="w-full sm:w-auto whitespace-nowrap p-3 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-hidden focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none" href="#">
                            Subscribe
                        </a>
                    </div>
                    <p class="mt-3 text-sm text-gray-400">
                        New UI kits or big discounts. Never spam.
                    </p>
                </form>
            </div>
            <!-- End Col -->
        </div>
        <!-- End Grid -->

        <div class="mt-5 sm:mt-12 grid gap-y-2 sm:gap-y-0 sm:flex sm:justify-between sm:items-center">
            <div class="flex flex-wrap justify-between items-center gap-2">
                <p class="text-sm text-gray-400 dark:text-neutral-400">
                    © 2025 Preline Labs.
                </p>
            </div>
            <!-- End Col -->

            <!-- Social Brands -->
            <div>
                <a class="size-10 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-white hover:bg-white/10 focus:outline-hidden focus:bg-white/10 disabled:opacity-50 disabled:pointer-events-none" href="{{ get_setting('facebook'); }}">
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z" />
                    </svg>
                </a>
                <a class="size-10 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-white hover:bg-white/10 focus:outline-hidden focus:bg-white/10 disabled:opacity-50 disabled:pointer-events-none" href="{{ get_setting('linkedin'); }}" target="_blank" rel="noopener noreferrer">
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M0 1.146C0 .513.324 0 .725 0h14.55c.4 0 .725.513.725 1.146v13.708c0 .633-.325 1.146-.725 1.146H.725A.723.723 0 0 1 0 14.854V1.146zm4.943 12.248V6.169H2.542v7.225h2.401zm-1.2-8.209c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248-.822 0-1.358.54-1.358 1.248 0 .694.52 1.248 1.327 1.248h.015zm4.908 8.209V9.359c0-.213.015-.426.08-.578.174-.426.571-.868 1.238-.868.873 0 1.222.655 1.222 1.615v3.866h2.401V9.25c0-2.22-1.185-3.252-2.767-3.252-1.276 0-1.845.701-2.165 1.194v.026h-.015a5.33 5.33 0 0 1 .015-.026V6.169h-2.4c.03.7 0 7.225 0 7.225h2.4z"/>
                    </svg>
                </a>
                <a class="size-10 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-white hover:bg-white/10 focus:outline-hidden focus:bg-white/10 disabled:opacity-50 disabled:pointer-events-none" href="{{ get_setting('x_twitter'); }}">
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z" />
                    </svg>
                </a>
                <a class="size-10 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-white hover:bg-white/10 focus:outline-hidden focus:bg-white/10 disabled:opacity-50 disabled:pointer-events-none" href="{{ get_setting('instagram'); }}" target="_blank" rel="noopener noreferrer">
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 0C5.8 0 5.5.01 4.7.05 3.9.1 3.3.24 2.8.42c-.5.2-.9.44-1.3.84-.4.4-.64.8-.84 1.3-.18.5-.32 1.1-.37 1.9C.01 5.5 0 5.8 0 8c0 2.2.01 2.5.05 3.3.05.8.19 1.4.37 1.9.2.5.44.9.84 1.3.4.4.8.64 1.3.84.5.18 1.1.32 1.9.37.8.04 1.1.05 3.3.05s2.5-.01 3.3-.05c.8-.05 1.4-.19 1.9-.37.5-.2.9-.44 1.3-.84.4-.4.64-.8.84-1.3.18-.5.32-1.1.37-1.9.04-.8.05-1.1.05-3.3s-.01-2.5-.05-3.3c-.05-.8-.19-1.4-.37-1.9a3.284 3.284 0 0 0-.84-1.3c-.4-.4-.8-.64-1.3-.84-.5-.18-1.1-.32-1.9-.37C10.5.01 10.2 0 8 0zM8 1.5c2.1 0 2.4.01 3.2.05.7.04 1.1.18 1.4.3.4.15.7.33 1 .64.3.3.5.6.64 1 .12.3.26.7.3 1.4.04.8.05 1.1.05 3.2s-.01 2.4-.05 3.2c-.04.7-.18 1.1-.3 1.4a2.21 2.21 0 0 1-.64 1c-.3.3-.6.5-1 .64-.3.12-.7.26-1.4.3-.8.04-1.1.05-3.2.05s-2.4-.01-3.2-.05c-.7-.04-1.1-.18-1.4-.3a2.21 2.21 0 0 1-1-.64 2.21 2.21 0 0 1-.64-1c-.12-.3-.26-.7-.3-1.4C1.51 10.4 1.5 10.1 1.5 8s.01-2.4.05-3.2c.04-.7.18-1.1.3-1.4a2.21 2.21 0 0 1 .64-1c.3-.3.6-.5 1-.64.3-.12.7-.26 1.4-.3C5.6 1.51 5.9 1.5 8 1.5zM8 3.8a4.2 4.2 0 1 0 0 8.4 4.2 4.2 0 0 0 0-8.4zm0 6.9a2.7 2.7 0 1 1 0-5.4 2.7 2.7 0 0 1 0 5.4zm4.5-6.9a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                    </svg>
                </a>
            </div>
            <!-- End Social Brands -->
        </div>
    </div>
</footer>
<!-- ========== END FOOTER ========== -->