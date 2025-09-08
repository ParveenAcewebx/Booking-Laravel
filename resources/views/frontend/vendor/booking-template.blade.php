<!-- Booking Template Form (hidden by default) -->
<div id="bookingTemplateModal" class="hidden">
    <div class="bg-white w-full rounded-lg relative">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Booking Template</h2>
            <button id="closeModal" class="text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>
        </div>
        <hr class="border-t border-gray-300 mb-4">

        <form id="templateForm">
            @csrf
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12">
                    <label class="block mb-1 font-medium">Template Name <span class="text-red-500">*</span></label>
                    <input type="text" name="template_name" id="bookingTemplatesname"
                        class="w-full border rounded p-2" placeholder="Enter name" required>
                </div>
                <div class="col-span-12 md:col-span-6">
                    <label class="block mb-1 font-medium">Select Vendor</label>
                    <select name="vendor_id" class="w-full border rounded p-2 select-template-vendor select2">
                        <option value="">-- Select Vendor --</option>
                        @foreach($activeVendor as $vendor)
                        <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-12 md:col-span-6">
                    <label class="block mb-1 font-medium">Status</label>
                    <select name="status" class="w-full border rounded p-2 select-template-status select2">
                        <option value="1" selected>Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>
            <div id="build-wrap" class="mt-4 rounded min-h-[400px]"></div>
        </form>
    </div>
</div>